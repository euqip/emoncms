var SteelseriesObjects = [];
/*
var framelist=          ["BLACK_METAL","METAL","SHINY_METAL","BRASS","STEEL","CHROME","GOLD","ANTHRACITE","TILTED_GRAY","TILTED_BLACK","GLOSSY_METAL"];
var backgroundcolour=   ["DARK_GRAY","SATIN_GRAY","LIGHT_GRAY","WHITE","BLACK","BEIGE","BROWN","RED","GREEN","BLUE","ANTHRACITE","MUD","PUNCHED_SHEET","CARBON","STAINLESS","BRUSHED_METAL","BRUSHED_STAINLESS","TURNED"];
var pointercolour=      ["RED","GREEN","BLUE","ORANGE","YELLOW","CYAN","MAGENTA","WHITE","GRAY","BLACK","RAITH","GREEN_LCD","JUG_GREEN"];
var LcdColor=           ["BEIGE","BLUE","ORANGE","RED","YELLOW","WHITE","GRAY","BLACK","GREEN","BLUE2","BLUE_BLACK","BLUE_DARKBLUE","BLUE_GRAY","STANDARD","STANDARD_GREEN","BLUE_BLUE","RED_DARKRED","DARKBLUE","LILA","BLACKRED","DARKGREEN" ];
var LedColor=           ["RED","GREEN","BLUE","ORANGE","YELLOW","CYAN","MAGENTA"];
var LinearTypeArray =   ["Linear","LinearBargraph","LinearThermoStat"];
var RadialTypeArray =   ["Radial","RadialBargraph","RadialVertical"];
var GenericTypeArray =  ["Compass","WindDirection","Level","Horizon","Led","Clock","Battery","Altimeter","Odometer","LightBulb","gradientWrapper","StopWatch"];

var selected_edges = {none : 0, left : 1, right : 2, top : 3, bottom : 4, center : 5};

    addOption(widgets["SSGeneric"], "feed",        "feed",          _Tr("Feed"),            _Tr("Feed value"),                                                                  []);
    addOption(widgets["SSGeneric"], "frame",       "dropbox",       _Tr("Frame"),           _Tr("Frame style"),                                                                 framelist);
    addOption(widgets["SSGeneric"], "scale",       "value",         _Tr("Scale"),           _Tr("Value is multiplied by scale before display. Defaults to 1"),                  []);
    addOption(widgets["SSGeneric"], "units",       "value",         _Tr("Units"),           _Tr("Unit type to show after value. Ex : <br>\"{Reading}{unit-string}\""),           []);
    addOption(widgets["SSGeneric"], "offset",      "value",         _Tr("Offset"),          _Tr("Static offset. Subtracted from value before computing position (default 0)"),  []);
    addOption(widgets["SSGeneric"], "colour",      "colour_picker", _Tr("Colour"),          _Tr("Colour to draw bar in"),                                                       []);
    addOption(widgets["SSGeneric"], "graduations", "dropbox",       _Tr("Graduations"),     _Tr("Should the graduations be shown"),                                             graduationDropBoxOptions);
    addOption(widgets["SSGeneric"], "gradNumber",  "value",         _Tr("Num Graduations"), _Tr("How many graduation lines to draw (only relevant if graduations are on)"),     []);

*/

function addOption(widget, optionKey, optionType, optionName, optionHint, optionData){
    widget["options"    ].push(optionKey);
    widget["optionstype"].push(optionType);
    widget["optionsname"].push(optionName);
    widget["optionshint"].push(optionHint);
    widget["optionsdata"].push(optionData);
}


function SSGeneric_widgetlist()
{
    var widgets =
    {
    "SSGeneric"                                                                                                                    :
        {
    "offsetx"                                                                                                                      : -80,"offsety"                                    : -80,"width" : 160,"height" : 160,
    "menu"                                                                                                                         : "Widgets",
    "options"                                                                                                                      : [],
    "optionstype"                                                                                                                  : [],
    "optionsname"                                                                                                                  : [],
    "optionshint"                                                                                                                  : [],
    "optionsdata"                                                                                                                  : []

        }
    };


var framelist=          [
                        ["BLACK_METAL","Black metal"],
                        ["METAL","metal"],
                        ["SHINY_METAL","Shiny metal"],
                        ["BRASS","Brass"],
                        ["STEEL","Steel"],
                        ["CHROME","Chrome"],
                        ["GOLD","Gold"],
                        ["ANTHRACITE","Anthracite"],
                        ["TILTED_GRAY","Tilted grey"],
                        ["TILTED_BLACK","Tilted black"],
                        ["GLOSSY_METAL","Glossy metal"]
                        ];
var backgroundcolour=   [
                        ["DARK_GRAY","Dark Grey"],
                        ["SATIN_GRAY","Satin Grey"],
                        ["LIGHT_GRAY","Light Grey"],
                        ["WHITE","White"],
                        ["BLACK","Black"],
                        ["BEIGE","Beige"],
                        ["BROWN","Brown"],
                        ["RED","Red"],
                        ["GREEN","Green"],
                        ["BLUE","Blue"],
                        ["ANTHRACITE","Anthracite"],
                        ["MUD","Mud"],
                        ["PUNCHED_SHEET","Punched sheet"],
                        ["CARBON","Carbon"],
                        ["STAINLESS","Stainless"],
                        ["BRUSHED_METAL","Brushed metal"],
                        ["BRUSHED_STAINLESS","Brushed stainless"],
                        ["TURNED","Turned"]
                        ];
var pointercolour=      [
                        ["RED","RED"],
                        ["GREEN","Green"],
                        ["BLUE","Blue"],
                        ["ORANGE","Orange"],
                        ["YELLOW","Yellow"],
                        ["CYAN","Cyan"],
                        ["MAGENTA","Magenta"],
                        ["WHITE","White"],
                        ["GRAY","Grey"],
                        ["BLACK","Black"],
                        ["RAITH","Raith"],
                        ["GREEN_LCD","Green LCD"],
                        ["JUG_GREEN","Jug Green"]
                        ];
var LcdColor=           [
                        ["BEIGE","Beige"],
                        ["BLUE","Blue"],
                        ["ORANGE","Orange"],
                        ["RED","Red"],
                        ["YELLOW","Yellow"],
                        ["WHITE","White"],
                        ["GRAY","Grey"],
                        ["BLACK","Black"],
                        ["GREEN","Green"],
                        ["BLUE2","Blue2"],
                        ["BLUE_BLACK","Blue Black"],
                        ["BLUE_DARKBLUE","Blue Dark blue"],
                        ["BLUE_GRAY","Blue grey"],
                        ["STANDARD","Standard"],
                        ["STANDARD_GREEN","Standard Green"],
                        ["BLUE_BLUE","Blue Blue"],
                        ["RED_DARKRED","Red Darked"],
                        ["DARKBLUE","Dark blue"],
                        ["LILA","LILA"],
                        ["BLACKRED","Black redr"],
                        ["DARKGREEN","Dark green" ]
                        ];
var LinearTypeArray =   [
                        ["Linear","Linear"],
                        ["LinearBargraph","Linear Bargraph"],
                        ["LinearThermoStat","Linear Thermosat"]
                        ];
var RadialTypeArray =   [
                        ["Radial","Radial"],
                        ["RadialBargraph","Radial Bargraph"],
                        ["RadialVertical","Radial Vertical"]
                        ];
var GenericTypeArray =  [
                        ["Compass","Compass"],
                        ["WindDirection","Wind direction"],
                        ["Level","Level"],
                        ["Horizon","Horizon"],
                        ["Led","Led"],
                        ["Clock","Clock"],
                        ["Battery","Battery"],
                        ["Altimeter","Altimeter"],
                        ["Odometer","Odometer"],
                        ["LightBulb","Light bulb"],
                        ["gradientWrapper","Gradient Wrapper"],
                        ["StopWatch","stopwatch"],
                        ["DisplaySingle","Single LCD Display"],
                        ["DisplayMulti","Dual LCD Display"],
                        ["TrafficLight","Trafic lights"]
                        ];
var LedColor=           [
                        ["RED","Red"],
                        ["GREEN","Green"],
                        ["BLUE","Blue"],
                        ["ORANGE","Orange"],
                        ["YELLOW","Yellow"],
                        ["CYAN","Cyan"],
                        ["MAGENTA","Magenta"]
                        ];


    addOption(widgets["SSGeneric"] , "feed"             , "feed"    , _Tr("Feed")                , _Tr("Feed value")                   , []);
    addOption(widgets["SSGeneric"] , "generictype"      , "dropbox" , _Tr("Instr layout")        , _Tr("The instrument layout")        , GenericTypeArray);
    addOption(widgets["SSGeneric"] , "frame"            , "dropbox" , _Tr("Frame")               , _Tr("frame style")                  , framelist);
    addOption(widgets["SSGeneric"] , "backgroundcolour" , "dropbox" , _Tr("Background colour")   , _Tr("Instrument background colour") , backgroundcolour);
    addOption(widgets["SSGeneric"] , "pointercolour"    , "dropbox" , _Tr("Pointer colour")      , _Tr("Pointer colour\"")             , pointercolour);
    addOption(widgets["SSGeneric"] , "LcdColor"         , "dropbox" , _Tr("LCD Colour")          , _Tr("LCD display colour")           , LcdColor);
    addOption(widgets["SSGeneric"] , "ledcolor"         , "dropbox" , _Tr("LED Colour")          , _Tr("The led color")                , LedColor);
    addOption(widgets["SSGeneric"] , "LinearTypeArray"  , "dropbox" , _Tr("Linear Type")         , _Tr("Linear type of array")         , LinearTypeArray);
    addOption(widgets["SSGeneric"] , "minvalue"         , "value"   , _Tr("Minimal scale value") , _Tr("Min scale value")              , []);
    addOption(widgets["SSGeneric"] , "maxvalue"         , "value"   , _Tr("Maximal scale value") , _Tr("Max scale value")              , []);
    addOption(widgets["SSGeneric"] , "lcddecimals"      , "value"   , _Tr("LCD decimals")        , _Tr("LCD decimals")                 , []);
    addOption(widgets["SSGeneric"] , "threshold"        , "value"   , _Tr("Threshold value")     , _Tr("threshold value")              , []);
    addOption(widgets["SSGeneric"] , "titel"            , "value"   , _Tr("Titel - Header")      , _Tr("Titel value")                  , []);
    addOption(widgets["SSGeneric"] , "unit"             , "value"   , _Tr("Units to display")    , _Tr("unit to display")              , []);



    return widgets;
}
/*
var LedColor=           ["RED","GREEN","BLUE","ORANGE","YELLOW","CYAN","MAGENTA"];
var framelist=          ["BLACK_METAL","METAL","SHINY_METAL","BRASS","STEEL","CHROME","GOLD","ANTHRACITE","TILTED_GRAY","TILTED_BLACK","GLOSSY_METAL"];
var backgroundcolour=   ["DARK_GRAY","SATIN_GRAY","LIGHT_GRAY","WHITE","BLACK","BEIGE","BROWN","RED","GREEN","BLUE","ANTHRACITE","MUD","PUNCHED_SHEET","CARBON","STAINLESS","BRUSHED_METAL","BRUSHED_STAINLESS","TURNED"];
var pointercolour=      ["RED","GREEN","BLUE","ORANGE","YELLOW","CYAN","MAGENTA","WHITE","GRAY","BLACK","RAITH","GREEN_LCD","JUG_GREEN"];
var LcdColor=           ["BEIGE","BLUE","ORANGE","RED","YELLOW","WHITE","GRAY","BLACK","GREEN","BLUE2","BLUE_BLACK","BLUE_DARKBLUE","BLUE_GRAY","STANDARD","STANDARD_GREEN","BLUE_BLUE","RED_DARKRED","DARKBLUE","LILA","BLACKRED","DARKGREEN" ];
var LinearTypeArray =   ["Linear","LinearBargraph","LinearThermoStat"];
var RadialTypeArray =   ["Radial","RadialBargraph","RadialVertical"];
var GenericTypeArray =  ["Compass","WindDirection","Level","Horizon","Led","Clock","Battery","Altimeter","Odometer","LightBulb","gradientWrapper","StopWatch"];
*/



function SSGeneric_widgetlist1()
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
         else if (generictype=="SingleDisplay"){
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
    width                                                                                                                          : $(this).width(),
    unitStringVisible                                                                                                              : unitstringbool,
    unitString                                                                                                                     : unitstring,
    headerString                                                                                                                   : headerString,
    headerStringVisible                                                                                                            : headerStringbool,
    valuesNumeric                                                                                                                  : true,
    digitalFont                                                                                                                    : true,
    lcdDecimals                                                                                                                    : lcdDecimals
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
    width                                                                                                                          : $(this).width(),
    unitStringVisible                                                                                                              : unitstringbool,
    unitString                                                                                                                     : unitstring,
    headerString                                                                                                                   : headerString,
    headerStringVisible                                                                                                            : headerStringbool,
    detailString                                                                                                                   : detailString,
    detailStringVisible                                                                                                            : detailStringbool,
    valuesNumeric                                                                                                                  : true,
    digitalFont                                                                                                                    : true,
    linkAltValue                                                                                                                   : false,
    lcdDecimals                                                                                                                    : lcdDecimals
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
                width                                                                                                                          : $(this).width(),
                unitStringVisible                                                                                                              : unitstringbool,
                unitString                                                                                                                     : unitstring,
                headerString                                                                                                                   : headerString,
                headerStringVisible                                                                                                            : headerStringbool,
                valuesNumeric                                                                                                                  : true,
                digitalFont                                                                                                                    : true,
                lcdDecimals                                                                                                                    : lcdDecimals,
                lcdVisible                                                                                                                     : true,
                autoScroll                                                                                                                     : true
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
             } else if (generictype=="DisplaySingle"){
            var unitstringbool=true;
            var unitstring = $(this).attr("unit");
                if (unitstring == undefined||unitstring ==""){
                    unitstring = "";
                    unitstringbool=false;
                }
              SteelseriesObjects[$(this).attr("id")] = new steelseries.DisplaySingle(id, {
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
             } else if (generictype=="DisplayMulti"){
                var unitstringbool=true;
                var unitstring = $(this).attr("unit");
                if (unitstring == undefined||unitstring ==""){
                    unitstring = "";
                    unitstringbool=false;
                }
              SteelseriesObjects[$(this).attr("id")] = new steelseries.DisplayMulti(id, {
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
             } else if (generictype=="TrafficLight"){
              SteelseriesObjects[$(this).attr("id")] = new steelseries.TrafficLight(id, {
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
//

        }
        // End of SSGeneric

    });
};