var SteelseriesObjects = [];



function SSGeneric_widgetlist()
{
  var widgets = {
    "SSGeneric":
    {
      "offsetx":-80,"offsety":-80,"width":200,"height":200,
      "menu":"Widgets",
      "options"		:["feed", "generictype", "type","framedesign", "backgroundcolour","pointercolour","PointerType","LcdColor","LedColor","ForegroundType","title", "unit", "threshold","sections","areas","minvalue","maxvalue"],
      "optionstype"	:["feed","generictype", "type", "framedesign", "backgroundcolour","pointercolour","PointerType","LcdColor","LedColor","ForegroundType","value","value","value", "sections", "areas","value","value"],
      "optionsname"	:[ _Tr("Feed"),_Tr("Generictype Selector"),  _Tr("Type Selector"), _Tr("Frame Design"), _Tr("Backgroundcolour"), _Tr("Pointercolour"), _Tr("PointerType"), _Tr("LcdColor"), _Tr("LedColor"),_Tr("ForegroundType"),_Tr("Title"),_Tr("Units"),_Tr("Threshold"),_Tr("Sections"),_Tr("Areas"),_Tr("Min Value"),_Tr("Max Value")],
      "optionshint"	:[_Tr(""),_Tr(""),_Tr("1/4, 1/2, 3/4, Full"),_Tr(""),_Tr(""),_Tr(""),_Tr(""),_Tr(""),_Tr(""),_Tr(""),_Tr("Title"),_Tr("Units to show"),_Tr("Led will Blink if Exceeded"),_Tr("Define section colours"),_Tr("Define area colours"),_Tr(""),_Tr(""),]
  }
}
return widgets;
}

function SSGeneric_init()
{
  setup_widget_canvas('SSGeneric');//add init
  setup_steelseries_object('SSGeneric');
}



function SSGeneric_draw()
{
	$('.SSGeneric').each(function(index){
	//REVISE
	var feed = $(this).attr("feed");
	if (feed==undefined){feed=0;}

	var val = assoc[feed];
  if (val==undefined) val = 0;


	if (val != temp){//redraw?
        try {
          var generictype = $(this).attr("generictype");
          if (generictype == undefined)  {  generictype="Compass"  };
              // Per ogni tipologia di controllo Steel esistente
              if (generictype=="Compass"){
                 if (val < 0)  val = 0;
                 val = val % 360;
                 SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
             }
             else if (generictype=="WindDirection"){
                 if (val < 0)  val = 0;
                 val = val % 360;
                 SteelseriesObjects[$(this).attr("id")].setValueAnimatedLatest(val);
                 SteelseriesObjects[$(this).attr("id")].setValueAnimatedAverage(val);
             }
             else if (generictype=="Level"){
                 if (val < 0)  val = 0;
                 val = val % 360;
                 SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
             }
             else if (generictype=="Horizon"){
                 if (val < -50)  val = -50;
                 val = val % 100;
                 SteelseriesObjects[$(this).attr("id")].setPitchAnimated(val);
                 SteelseriesObjects[$(this).attr("id")].setRollAnimated(val+10);
             }
             else if (generictype=="Led"){
                 if (val < 0)  val = 0;
                 val = val % 7;
                 var colore = "";
                 switch (val) {
                   case 0:
                   colore = "RED_LED";
                   break;
                   case 1:
                   colore = "GREEN_LED";
                   break;
                   case 2:
                   colore = "BLUE_LED";
                   break;
                   case 3:
                   colore = "ORANGE_LED";
                   break;
                   case 4:
                   colore = "YELLOW_LED";
                   break;
                   case 5:
                   colore = "CYAN_LED";
                   break;
                   case 6:
                   colore = "MAGENTA_LED";
                   break;
                   default:
                   colore = "RED_LED";
               }
               SteelseriesObjects[$(this).attr("id")].setLedColor(colore);
           }
           else if (generictype=="Clock"){
             SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
         }
         else if (generictype=="Battery"){
             SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
         }
         else if (generictype=="Altimeter"){
             SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
         }
         else if (generictype=="Odometer"){
             SteelseriesObjects[$(this).attr("id")].setValue(val);
         }
         else if (generictype=="LightBulb"){
                 //SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
                 SteelseriesObjects[$(this).attr("id")].setOn(val > 0);
                 SteelseriesObjects[$(this).attr("id")].setAlpha(val % 100);
             }
             else if (generictype=="gradientWrapper"){
               SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
           }
           else if (generictype=="StopWatch"){
               SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
           }

       }
       catch (err)
       {
          err = err;
      }
      var temp =val;
  }
});
}

function draw_SSGeneric(){

}

function SSGeneric_slowupdate()
{

}

function SSGeneric_fastupdate()
{
  SSGeneric_draw();
}


//TODO
// Values, render only on change
//MIN MAX VALUES
//Single JS load for steelseries.js
//linear scale lock?

function setup_steelseries_object(elementclass){
    $('.'+elementclass).each(function(index){
        var id = "can-"+$(this).attr("id"); //Canvas
        var title =$(this).attr("title");
        if (type == undefined){
            type = "";
        }

        var MinValue = new Number($(this).attr("MinValue"));
        if (MinValue == ""){
            MinValue = 0;
        }

        var MaxValue = new Number($(this).attr("MaxValue"));
        if (MaxValue == ""){
            MaxValue = 100;
        }

        var units =$(this).attr("unit");
        if (units == undefined){
            type = "";
        }

        var threshold = $(this).attr("threshold");
        if (threshold == undefined){
            threshold = 80;
        }

        var type = $(this).attr("type");
        if (type == undefined){
            type = "TYPE4";
        }

        //set section colours :D
        var sections = [
            steelseries.Section(00, 25, 'rgba(0, 0, 220, 0.3)'),
            steelseries.Section(25, 50, 'rgba(0, 220, 0, 0.3)'),
            steelseries.Section(50, 75, 'rgba(220, 220, 0, 0.3)')
            ];

        // Define one area colour :P
        var areas = Array(steelseries.Section(75, 100, 'rgba(220, 0, 0, 0.3)'));

        if (elementclass=="SSRadial"){
            //Checks Selection: "Radial","RadialBargraph","RadialVertical"
            var radialtype = $(this).attr("radialtype");
            if (radialtype == undefined){
                radialtype="Radial";
            }

            SteelseriesObjects[$(this).attr("id")] = new steelseries[radialtype](id, {
                gaugeType                 : steelseries.GaugeType[type],
                section                   : sections,
                size                      : $(this).width(),
                digitalFont               : true,
                area                      : areas,
                titleString               : title,
                unitString                : units,
                threshold                 : threshold,
                lcdVisible                : true,
                //fullScaleDeflectionTime : 0.5
                //minValue                : $(this).attr("minvalue"),
                //maxValue                : $(this).attr("maxvalue"),
                });


                //Pointer Exception Handle
            if (radialtype=="RadialBargraph"){
                var pointercolour = $(this).attr("pointercolour");
                if (pointercolour == undefined){
                    pointercolour = "RED";
                }
                SteelseriesObjects[$(this).attr("id")].setValueColor(steelseries.ColorDef[pointercolour]);
            } else {
                var PointerType = $(this).attr("pointertype");
                if (PointerType == undefined){
                    PointerType = "TYPE1";
                }
                SteelseriesObjects[$(this).attr("id")].setPointerType(steelseries.PointerType[PointerType]);

                var pointercolour = $(this).attr("pointercolour");
                if (pointercolour == undefined){
                    pointercolour = "RED";
                }
                SteelseriesObjects[$(this).attr("id")].setPointerColor(steelseries.ColorDef[pointercolour]);

                var ForegroundType = $(this).attr("ForegroundType");
                if (ForegroundType == undefined){
                    ForegroundType = "TYPE1";
                }
                SteelseriesObjects[$(this).attr("id")].setForegroundType(steelseries.ForegroundType[ForegroundType]);
            }
                  //End Exception Handle

            if (radialtype=="RadialVertical"){
                //Skip LCD Colour
            } else {
                var LcdColor = $(this).attr("LcdColor");
                if (LcdColor == undefined){
                    LcdColor = "STANDARD";
                }
                SteelseriesObjects[$(this).attr("id")].setLcdColor(steelseries.LcdColor[LcdColor]);
            }
        } else if (elementclass=="SSSingleDisplay"){
            var unitstringbool=true;
            var unitstring = $(this).attr("unit");
            if (unitstring == undefined||unitstring ==""){
                unitstring = "";
                unitstringbool=false;
            }

            var headerStringbool=true;
            var headerString = $(this).attr("headerString");
            if (headerString == undefined||headerString==""){
                headerString = "";
                headerStringbool=false;
            }


            //decimal checks?
            var lcdDecimals = $(this).attr("lcdDecimals");
            if (lcdDecimals == undefined || lcdDecimals == ""||lcdDecimals>10||lcdDecimals<0){
                lcdDecimals = 2;
            }
            SteelseriesObjects[$(this).attr("id")] = new steelseries.DisplaySingle(id, {
            width               : $(this).width(),
            unitStringVisible   : unitstringbool,
            unitString          : unitstring,
            headerString        : headerString,
            headerStringVisible : headerStringbool,
            valuesNumeric       : true,
            digitalFont         : true,
            lcdDecimals         : lcdDecimals
                });
              //End of SingleDisplay Object Init
        } else if (elementclass=="SSMultiDisplay"){
            //Start of MultiDisplay Object Init

            var unitstringbool=true;
            var unitstring = $(this).attr("unitString");
            if (unitstring == undefined||unitstring ==""){
                unitstring = "";
                unitstringbool=false;
            }

            var headerStringbool=true;
            var headerString = $(this).attr("headerString");
            if (headerString == undefined||headerString==""){
                headerString = "";h
                eaderStringbool=false;
            }

            var detailStringbool=true;
            var detailString = $(this).attr("detailString");
            if (detailString == undefined ||detailString ==""){
                detailString = "";
                detailStringbool=false;
            }

            //decimal checks?
            var lcdDecimals = $(this).attr("lcdDecimals");
            if (lcdDecimals == undefined || lcdDecimals == ""||lcdDecimals>10||lcdDecimals<0){
                lcdDecimals = 2;
            }

            SteelseriesObjects[$(this).attr("id")] = new steelseries.DisplayMulti(id, {
            width               : $(this).width(),
            unitStringVisible   : unitstringbool,
            unitString          : unitstring,
            headerString        : headerString,
            headerStringVisible : headerStringbool,
            detailString        : detailString,
            detailStringVisible : detailStringbool,
            valuesNumeric       : true,
            digitalFont         : true,
            linkAltValue        : false,
            lcdDecimals         : lcdDecimals
                });
            //End of MultiDisplay Object Init
        } else if (elementclass=="SSLinear"){
            //Start of SSLinear Object Init
            var LinearTypeSelector = $(this).attr("LinearType");
            if (LinearTypeSelector == undefined){
                LinearTypeSelector = "Linear";
            }

            if (LinearTypeSelector=="Linear"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Linear(id, {
                titleString : title,
                unitString  : units,
                width       : $(this).width(),
                height      : $(this).height(),
                unitString  : units,
                lcdVisible  : true,
                threshold   : threshold
                });
            } else if (LinearTypeSelector=="LinearBargraph"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.LinearBargraph(id, {
                titleString: title,
                unitString : units,
                width      : $(this).width(),
                height     : $(this).height(),
                unitString : units,
                lcdVisible : true,
                threshold  : threshold
                });
            } else if (LinearTypeSelector=="LinearThermoStat"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Linear(id, {
                gaugeType               : steelseries.GaugeType.TYPE2, //ThermoStat Property
                unitString              : units,
                titleString             : title,
                width                   : $(this).width(),
                height                  : $(this).height(),
                unitString              : units,
                lcdVisible              : true,
                threshold               : threshold,
                minValue                : MinValue,
                maxValue                : MaxValue,
                fullScaleDeflectionTime : 0.8
                });
            }

            var pointercolour = $(this).attr("pointercolour");
            if (pointercolour == undefined){
                pointercolour = "RED";
            }
            SteelseriesObjects[$(this).attr("id")].setValueColor(steelseries.ColorDef[pointercolour]);
        }
        //End of SSLinear Object Init
        if (elementclass=="SSSingleDisplay"||elementclass=="SSMultiDisplay"){
            var LcdColor = $(this).attr("LcdColor");
            if (LcdColor == undefined){
                LcdColor = "STANDARD";
            }
            SteelseriesObjects[$(this).attr("id")].setLcdColor(steelseries.LcdColor[LcdColor]);
        }

        if (elementclass=="SSLinear" || elementclass=="SSRadial"){
            var framedesign = $(this).attr("framedesign");
            if (framedesign == undefined) {
                framedesign = "METAL";
            }
            SteelseriesObjects[$(this).attr("id")].setFrameDesign(steelseries.FrameDesign[framedesign]);

            var backgroundcolour = $(this).attr("backgroundcolour");
            if (backgroundcolour == undefined) {
                backgroundcolour = "DARK_GRAY";
            }
            SteelseriesObjects[$(this).attr("id")].setBackgroundColor(steelseries.BackgroundColor[backgroundcolour]);

            var LedColor = $(this).attr("LedColor");
            if (LedColor == undefined) {
                LedColor = "RED";
            }
            SteelseriesObjects[$(this).attr("id")].setLedColor(steelseries.LedColor[LedColor+"_LED"]);
        }

        // Start of SSGeneric
        if (elementclass=="SSGeneric"){
            var generictype = $(this).attr("generictype");
            if (generictype == undefined) {
              generictype="Compass";
            }
            if (generictype=="Compass"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Compass(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="WindDirection"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.WindDirection(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                degreeScaleHalf     : true,
                autoScroll          : true
                });
            } else if (generictype=="Level"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Level(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="Horizon"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Horizon(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="Led"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Led(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="Clock"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Clock(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                  });
            } else if (generictype=="Battery"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Battery(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="Altimeter"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Altimeter(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="Odometer"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.Odometer(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="LightBulb"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.LightBulb(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="gradientWrapper"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.gradientWrapper(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            } else if (generictype=="StopWatch"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.StopWatch(id, {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                autoScroll          : true
                });
            }
        }
        // End of SSGeneric

    });
};