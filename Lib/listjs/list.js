/*

  list.js is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org

  */
/* jshint undef: true, unused: true */
/* global $, CryptoJS */

  var list = {

    'data':{},
    'fields':{},
    'element':"#table",
    'timezones':{},

    'init':function()
    {
        var table = $('<table class="table table-hover" />'),
        tr,  tooltip;
        for (field in list.fields) {
            var fld= list.fields[field];
            tooltip = (fld.tooltip!==undefined) ? fld.tooltip : 'Edit';
            tr = $("<tr />").attr("field", field);
            tr.append('  <td type="name" class="text-muted" style="width:150px;">'+fld.title+'</td>');
            tr.append('  <td type="edit" title = "'+tooltip+'" action="edit" style="width:40px;"> <span class="glyphicon glyphicon-pencil" style="display:none"> </span></td>');
            tr.append('  <td type="value" class="">'+(list.fieldtypes[fld.type].draw(list.data[field])||'N/A')+'</td>');
            table.append(tr);
        }
        $(list.element).html(table);

        $(list.element+" td[type=edit]").click(function() {
            var action = $(this).attr('action');
            var field = $(this).parent().attr('field');
            var fld = list.fields[field];

            if (action=='edit')
            {
              tooltip = (fld.tooltip!==undefined) ? fld.tooltip : '';
              $(list.element+" tr[field="+field+"] td[type=value]").html(list.fieldtypes[fld.type].edit(field,list.data[field]));
              $(this).html('<span class="glyphicon glyphicon-floppy-save"></span>').attr('action','save');
          }

          if (action=='save')
          {
              list.data[field] = list.fieldtypes[fld.type].save(field);
              tooltip = (fld.tooltip!==undefined) ? fld.tooltip : '';
              //list.data[field] is a number in case of idselect, should be replaced by its representation
              $(list.element+" tr[field="+field+"] td[type=value]").html(list.fieldtypes[fld.type].draw(list.data[field]));
              $(this).html("<span class='glyphicon glyphicon-pencil'  title = '"+tooltip+"' style='display:none'></span>").attr('action','edit');
              $(list.element).trigger("onSave",[]);
              // in case of idselect the selected value is to be stored, but the corresponding text is to be shown
              //if (fld.type == 'idselect'){
              if (fld.toshow !==undefined){
                //$(list.element+" tr[field="+field+"] td[type=value]").html(fld.options[list.data[field]]);
                $(list.element+" tr[field="+field+"] td[type=value]").html(fld.toshow);
              }
          }
      });

        // Show edit button only on hover
        $(list.element+" tr").hover(
          function() {
            $(this).find(".glyphicon").show();
        },
        function() {
            $(this).find(".glyphicon").hide();
        }
        );
    },

    'fieldtypes':    {
      'text':      {
        'draw':function(value) {
          return "<span>"+value+"</span>";
        },
        'edit':function(field,value) {
          var fld=list.fields[field];
          var tooltip = (fld.tooltip!==undefined) ? fld.tooltip : '';
          return "<input type='text' class='form-control' title='"+tooltip+"' value='"+(value||'')+"' / >"; },
        'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] input').val();}
      },

      'checkbox':      {
        'draw': function (value)  { return value; },
        'edit': function (field,value) {
          var fld=list.fields[field];
          var tooltip = (fld.tooltip!==undefined) ? fld.tooltip : '';
          return "<input type='checkbox' class='form-control' title='"+tooltip+"' value='"+(value||'')+"' / >";
        },
        'save': function (field) { return $(list.element+' tr[field='+field+'] td[type=value] input').val();},
      },

      'select':      {
        'draw':function(value) { return value; },
        'edit':function(field,value)
        {
          var options = '';
          var fld=list.fields[field];
          var tooltip = (fld.tooltip!==undefined) ? fld.tooltip : '';
          for (var i=0; i< fld.options.length; i++)
          //for (var i in fld.options)
          {
            var selected = "";  if (fld.options[i] == value) selected = 'selected';
            options += "<option value="+fld.options[i]+" "+selected+">"+fld.options[i]+"</option>";
        }
        return "<select class='form-control' title='"+tooltip+"'>"+options+"</select>";
        },
        'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] select').val();}
      },

      'language':
        {
          'draw':function(value) {
            // the label parameter comes in the drop down list
            for (var i in list.fields.language.options)
            {
              if (list.fields.language.options[i] == value) return list.fields.language.label[i];
            }
          },
          'edit':function(field,value)
          {
            var options = '';
            var fld=list.fields[field];
            var tooltip = (fld.tooltip!==undefined) ? fld.tooltip : '';
            for (var i =0; i<fld.options.length; i++)
            //for (var i in fld.options)
            {
              var selected = "";
              if (fld.options[i] == value) selected = 'selected';
              options += "<option value="+fld.options[i]+" "+selected+">"+fld.label[i]+"</option>";
            }
            return "<select class='form-control' title='"+tooltip+"'>"+options+"</select>";
          },
          'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] select').val();}
        },



      'tblselect':      {
        /**
        **  This type of field will get its selection in a table provided by a model
        **  The model returned data are an ID and a display text
        **/
        'draw':function(value)        {
            for (var i=0; i< list.fields[field].options.length; i++)
            //for (var i in list.fields[field].options)
            {
              var fld= list.fields[field].options[i];
              if (fld.id==value){
                return fld.toshow;
            }
        }
        return "";
        },
        'edit':function(field,value)        {
          var fld1=list.fields[field];
          var tooltip = (fld1.tooltip!==undefined) ? fld1.tooltip : '';
          var options = '';
          for (var i=0; i< fld1.options.length; i++)
          //for (var i in list.fields[field].options)
          //for (var i in fld1.options)
          {
            var fld= list.fields[field].options[i];
            var selected = ""; if (fld.id == value) selected = 'selected';
            options += "<option value="+fld.id+" "+selected+">"+fld.toshow+"</option>";
            }
          return "<select class='form-control' title='"+tooltip+"'>"+options+"</select>";
          },
          'save':function(field)          {
            return $(list.element+' tr[field='+field+'] td[type=value] select').val();
          }
        },

        'idselect':        {
            /**
            **  This type of field will get its selection in a json string with IDs
            **
            **/
            'draw':function(value)    {
                return list.fields[field].options[value];
            },
            'edit':function(field,value)    {
                var options = '';
                var fld1=list.fields[field];
                var tooltip   = (fld1.tooltip!==undefined) ? fld1.tooltip : '';
                for (var i =0; i<fld1.options; i++)
                //for (var i in fld1.options)
                {
                    var fld= fld1.options[i];
                    var selected = ""; if (i == value) selected = 'selected';
                    options += "<option value="+i+" "+selected+">"+fld+"</option>";
                }
                return "<select class='form-control' title='"+tooltip+"'>"+options+"</select>";
            },
            'save':function(field) {
              var fld=list.fields[field];
              fld.toshow =  fld.options[$(list.element+' tr[field='+field+'] td[type=value] select').val()];
              return $(list.element+' tr[field='+field+'] td[type=value] select').val();
            }
        },

        'timezone':
        {
          'draw':function(value)
          {
            return value;
          },
          'edit':function(field,value)
          {
          var fld1=list.fields[field];
          var tooltip = (fld1.tooltip!==undefined) ? fld1.tooltip : '';
          var options = '';
          var selectedIndex = null;
            for (var i=0; i< list.timezones.length; i++) {
            //for (var i in list.timezones) {
              var tz = list.timezones[i];
              var selected = "";
              if (value == tz.id) {
                selected = 'selected';
                selectedIndex = tz.id;
              }
              options = options+ "<option value="+tz.id+" "+selected+">"+tz.id+" ("+tz.gmt_offset_text+")</option>";
              //select.append("<option value="+tz.id+" "+selected+">"+tz.id+" ("+tz.gmt_offset_text+")</option>");
            }
            //If no selected index were set, then default to UTC
            return "<select class='form-control' title='"+tooltip+"'>"+options+"</select>";
            //return select.wrap('<p>').parent().html();  //return HTML-string
          },
          'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] select').val();}
        },

        'gravatar':      {
          'draw':function(value) { return "<img style='border: 1px solid #ccc; padding:2px;' src='http://www.gravatar.com/avatar/"+CryptoJS.MD5(value)+"'/ >" ;},
          'edit':function(field,value) { return "<input class='form-control' type='text' value='"+value+"' / >" ;},
          'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] input').val();}
        }
      }
    };