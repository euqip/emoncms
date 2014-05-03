/*

  list.js is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org

  */

  var list = {

    'data':{},
    'fields':{},
    'element':"#table",

    'init':function()
    {
        var table = $('<table class="table table-hover" />'),
        tr;
        for (field in list.fields) {
            var fld= list.fields[field];
            var tooltip = 'Edit'; if (fld.tooltip!=undefined) tooltip = fld.tooltip;
            tr = $("<tr />").attr("field", field);
            tr.append('  <td type="name" class="text-muted" style="width:150px;">'+fld.title+'</td>');
            tr.append('  <td type="edit" title = "'+tooltip+'" action="edit" style="width:32px;"> <span class="glyphicon glyphicon-pencil" style="display:none"> </span></td>');
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
              var tooltip = ''; if (fld.alt!=undefined) tooltip = fld.alt;
              $(list.element+" tr[field="+field+"] td[type=value]").html(list.fieldtypes[list.fields[field].type].edit(field,list.data[field]));
              $(this).html('<span class="glyphicon glyphicon-floppy-save"></span>').attr('action','save');
          }

          if (action=='save')
          {
              list.data[field] = list.fieldtypes[fld.type].save(field);
              var tooltip = ''; if (fld.tooltip!=undefined) tooltip = fld.tooltip;
              $(list.element+" tr[field="+field+"] td[type=value]").html(list.fieldtypes[list.fields[field].type].draw(list.data[field]));
              $(this).html("<span class='glyphicon glyphicon-pencil'  title = '"+tooltip+"' style='display:none'></span>").attr('action','edit');
              $(list.element).trigger("onSave",[]);
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

    'fieldtypes':
    {
        'text':
        {
          'draw':function(value) { return value; },
          'edit':function(field,value) { return "<input type='text' class='form-control' value='"+(value||'')+"' / >"; },
          'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] input').val();}
      },

      'select':
      {
          'draw':function(value) { return value },
          'edit':function(field,value)
          {
            var options = '';
            for (i in list.fields[field].options)
            {
              var selected = ""; if (list.fields[field].options[i] == value) selected = 'selected';
              options += "<option value="+list.fields[field].options[i]+" "+selected+">"+list.fields[field].options[i]+"</option>";
          }
          return "<select class='form-control'>"+options+"</select>";
      },
      'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] select').val();}
  },

  'tblselect':
  {
        /**
        **  This type of field will get its selection in a table provided by a model
        **  The model returned data are an ID and a display text
        **/
        'draw':function(value)
        {
            for (i in list.fields[field].options)
            {
              var fld= list.fields[field].options[i];
              if (fld.id==value){
                return fld.toshow;
            }
        }
        return "";
    },
    'edit':function(field,value)
    {
        var options = '';
        for (i in list.fields[field].options)
        {
          var fld= list.fields[field].options[i];
          var selected = ""; if (fld.id == value) selected = 'selected';
          options += "<option value="+fld.id+" "+selected+">"+fld.toshow+"</option>";
      }
      return "<select class='form-control'>"+options+"</select>";
  },
  'save':function(field) 
  {
    return $(list.element+' tr[field='+field+'] td[type=value] select').val();
  }
},

'timezone':
{
  'draw':function(value)
  {
    var sign = value >= 0 ? '+' : '';
    return "UTC "+sign+(value||0)+":00";
},
'edit':function(field,value)
{
    var select = $('<select class="form-control"/>'),
    selectedIndex = null,
    sign;

    for (var i=-12; i<=14; i++) {
      var selected = "";
      if (value == i) {
        selected = 'selected';
        selectedIndex = i;
    }
    sign = i >= 0 ? '+' : '';
    select.append("<option value="+i+" "+selected+">UTC "+sign+i+":00</option>");
}
            //If no selected index were set, then default to 0
            if ( selectedIndex === null ) {
                select.find("option[value='0']").attr('selected', 'selected');
            }
            return select.wrap('<p>').parent().html();  //return HTML-string
        },
        'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] select').val();}
    },

    'gravatar':
    {
      'draw':function(value) { return "<img style='border: 1px solid #ccc; padding:2px;' src='http://www.gravatar.com/avatar/"+CryptoJS.MD5(value)+"'/ >" },
      'edit':function(field,value) { return "<input class='form-control' type='text' value='"+value+"' / >" },
      'save':function(field) { return $(list.element+' tr[field='+field+'] td[type=value] input').val();}
  }
}
}
