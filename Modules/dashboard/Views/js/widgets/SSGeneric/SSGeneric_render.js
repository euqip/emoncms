var SteelseriesObjects = [];
/* Possible SteelseriesObjects 
Altimeter: function (canvas, parameters) {
BackgroundColor: Object
Battery: function (canvas, parameters) {
Clock: function (canvas, parameters) {
ColorDef: Object
Compass: function (canvas, parameters) {
ConicalGradient: function (fractions, colors) {
DisplayMulti: function (canvas, parameters) {
DisplaySingle: function (canvas, parameters) {
ForegroundType: Object
FrameDesign: Object
GaugeType: Object
Horizon: function (canvas, parameters) {
KnobStyle: Object
KnobType: Object
LabelNumberFormat: Object
LcdColor: Object
Led: function (canvas, parameters) {
LedColor: Object
Level: function (canvas, parameters) {
LightBulb: function (canvas, parameters) {
Linear: function (canvas, parameters) {
LinearBargraph: function (canvas, parameters) {
Odometer: function (canvas, parameters) {
Orientation: Object
PointerType: Object
Radial: function (canvas, parameters) {
RadialBargraph: function (canvas, parameters) {
RadialVertical: function (canvas, parameters) {
Section: function section(start, stop, color) {
StopWatch: function (canvas, parameters) {
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
    "SSGeneric"   :
        {
        //"offsetx"     : -80       , "offsety" : -80 , "width" : 160 , "height" : 160 ,
        "offsetx"     : 0       ,
        "offsety"     : 0 ,
        "width"       : 60 ,
        "height"      : 60 ,
        "menu"        : "Widgets" ,
        "options"     : [],
        "optionstype" : [],
        "optionsname" : [],
        "optionshint" : [],
        "optionsdata" : []
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
                        ["TrafficLight","Trafic lights"],
                        ['LinearBargraph','Linear Bar Graph'],
                        //['LinearThermoStat', 'Linear Thermostat'],
                        ["RadialBargraph","Radial bar graph"]
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


    addOption(widgets["SSGeneric"] , "feed"             , "feed"    , _Tr("Main Feed")           , _Tr("Main Feed value")              , []);
    addOption(widgets["SSGeneric"] , "feed"             , "feed"    , _Tr("secondary Feed")      , _Tr("Secondary Feed value")         , []);
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
    addOption(widgets["SSGeneric"] , "title"            , "value"   , _Tr("Title - Header")      , _Tr("Title value")                  , []);
    addOption(widgets["SSGeneric"] , "unit"             , "value"   , _Tr("Units to display")    , _Tr("unit to display")              , []);



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
         else if (generictype=="DisplaySingle"){
             SteelseriesObjects[$(this).attr("id")].setValueAnimated(val);
         }
         else if (generictype=="DisplayMulti"){
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
            //var lcdcolors       = new steelseries.LcdColor
            var generictype     =($(this).attr("generictype") === undefined) ? "DisplaySingle" : $(this).attr("generictype");
            var myLcdColor      =($(this).attr("lcdcolor") === undefined) ? "STANDARD" : $(this).attr("lcdcolor");
            var lcdDecimals     =($(this).attr("lcddecimals") === undefined) ? 2 : $(this).attr("lcddecimals");
            lcdDecimals         =(lcdDecimals === ""||lcdDecimals>10||lcdDecimals<0) ? 2 : $(this).attr("lcdDecimals");
            var unitstring      =($(this).attr("unit") === undefined) ? "" : $(this).attr("unit");
            var unitstringbool  =(unitstring==="") ? false : true;
            var headerString    =($(this).attr("title") === undefined) ? "" : $(this).attr("title");
            var headerStringbool=(headerString==="") ? false : true;

            var params= {
                width               : $(this).width(),
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                //lcdColor            : lcdcolors[myLcdColor]
                };

            SteelseriesObjects[$(this).attr("id")] = new steelseries[generictype](id, params);
            switch (generictype){
            case "DisplaySingle":
                SteelseriesObjects[$(this).attr("id")].setLcdColor(steelseries.LcdColor[myLcdColor]);
                break;
            case "DisplayMulti":
                SteelseriesObjects[$(this).attr("id")].setLcdColor(steelseries.LcdColor[myLcdColor]);
                break;
            default:
           }
        }
        // End of SSGeneric

    });
};