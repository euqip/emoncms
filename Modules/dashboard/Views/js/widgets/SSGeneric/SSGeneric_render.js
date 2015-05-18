/* jshint undef: true, unused: true */
/* global _Tr, $, assoc, setup_widget_canvas, steelseries*/
'use strict';
var SObjects = [];
/* Possible SObjects 
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
    widget.options.push(optionKey);
    widget.optionstype.push(optionType);
    widget.optionsname.push(optionName);
    widget.optionshint.push(optionHint);
    widget.optionsdata.push(optionData);
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
        "aspectRatio" : true ,
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
                        ["Clock","Analog Clock"],
                        ["Battery","Battery"],
                        //["altimeter","Altimeter"],
                        ["Odometer","Odometer"],
                        ["LightBulb","Light bulb"],
                        ["gradientWrapper","Gradient Wrapper"],
                        ["StopWatch","stopwatch"],
                        ["DisplaySingle","Single LCD Display"],
                        ["DisplayMulti","Dual LCD Display"],
                        ["TrafficLight","Trafic lights"],
                        ['LinearBargraph','Linear Bar Graph'],
                        //['LinearThermoStat', 'Linear Thermostat'],
                        ["RadialBargraph","Radial bar graph"],
                        ["Radial","Radial"]
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
var ForegroundStyle=    [
                        ["TYPE1","Type1, Neutral"],
                        ["TYPE2","Type2 Grey Top Bottom"],
                        ["TYPE3","Type3"],
                        ["TYPE4","Type4 Top left reflection"],
                        ["TYPE5","Type5 Top right reflection"],
                        ];


    addOption(widgets.SSGeneric , "feed"             , "feed"    , _Tr("Main Feed")           , _Tr("Main Feed value")              , []);
    addOption(widgets.SSGeneric , "feed"             , "feed"    , _Tr("secondary Feed")      , _Tr("Secondary Feed value")         , []);
    addOption(widgets.SSGeneric , "generictype"      , "dropbox" , _Tr("Instr layout")        , _Tr("The instrument layout")        , GenericTypeArray);
    addOption(widgets.SSGeneric , "frame"            , "dropbox" , _Tr("Frame")               , _Tr("frame style")                  , framelist);
    addOption(widgets.SSGeneric , "backgroundcolour" , "dropbox" , _Tr("Background colour")   , _Tr("Instrument background colour") , backgroundcolour);
    addOption(widgets.SSGeneric , "pointercolour"    , "dropbox" , _Tr("Pointer colour")      , _Tr("Pointer colour")               , pointercolour);
    addOption(widgets.SSGeneric , "ForegroundType"   , "dropbox" , _Tr("light effects")       , _Tr("The bezel light refections")   , ForegroundStyle);
    addOption(widgets.SSGeneric , "LcdColor"         , "dropbox" , _Tr("LCD Colour")          , _Tr("LCD display colour")           , LcdColor);
    addOption(widgets.SSGeneric , "ledcolor"         , "dropbox" , _Tr("LED Colour")          , _Tr("The led color")                , LedColor);
    //addOption(widgets.SSGeneric , "LinearTypeArray"  , "dropbox" , _Tr("Linear Type")         , _Tr("Linear type of array")         , LinearTypeArray);
    addOption(widgets.SSGeneric , "minvalue"         , "value"   , _Tr("Minimal scale value") , _Tr("Min scale value")              , []);
    addOption(widgets.SSGeneric , "maxvalue"         , "value"   , _Tr("Maximal scale value") , _Tr("Max scale value")              , []);
    addOption(widgets.SSGeneric , "lcddecimals"      , "value"   , _Tr("LCD decimals")        , _Tr("LCD decimals")                 , []);
    addOption(widgets.SSGeneric , "threshold"        , "value"   , _Tr("Threshold value")     , _Tr("threshold value")              , []);
    addOption(widgets.SSGeneric , "title"            , "value"   , _Tr("Title - Header")      , _Tr("Title value")                  , []);
    addOption(widgets.SSGeneric , "unit"             , "value"   , _Tr("Units to display")    , _Tr("unit to display")              , []);



    return widgets;
}
function defaultInstrument(){
    return "DisplaySingle";
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
    var feed        =($(this).attr("feed") === undefined) ? 0 : $(this).attr("feed");
    var generictype =($(this).attr("generictype") === undefined) ? defaultInstrument() : $(this).attr("generictype");
    var val         =(assoc[feed] === undefined) ? 0 : assoc[feed];
    var temp        =($(this).attr("old") === undefined) ? 0 : $(this).attr("old");
    var id          = $(this).attr("id");
    if (val != temp){//redraw?
        //store present value as attribute
        //bypass refresh if no change
        $(this).attr("old",val);
    try {
              // Per ogni tipologia di controllo Steel esistente
              // Sets the animated value as the default behavior
            switch (generictype){
                case "Compass":
                    val = (val <0)?0:val %360;
                    SObjects[id].setValueAnimated(val);
                    break;
                case "WindDirection":
                    val = (val <0)?0:val %360;
                    SObjects[id].setValueAnimatedLatest(val);
                    SObjects[id].setValueAnimatedAverage(val);
                    break;
                case "Level":
                    val = (val <0)?0:val %360;
                    SObjects[id].setValueAnimated(val);
                    break;
                case "Horizon":
                    val = (val <-50)?-50:val %100;
                    SObjects[id].setPitchAnimated(val);
                    SObjects[id].setRollAnimated(val+10);
                    break;
                case "Led":
                    val = (val <0)?0:val %7;
                    var LedColorvalues=     ["RED","GREEN","BLUE","ORANGE","YELLOW","CYAN","MAGENTA"];
                    SObjects[id].setLedColor(LedColorvalues[val]+"_LED");
                    break;
                case "Odometer":
                    SObjects[id].setValue(val);
                    break;
                case "LightBulb":
                     //SObjects[id].setValueAnimated(val);
                    SObjects[id].setOn(val > 0);
                    SObjects[id].setAlpha(val % 100);
                case "Clock":
                    console.log(generictype);
                    break;
                case "LinearBargraph":
                    SObjects[id].setValueAnimated(val);
                    break;
                default:
                    SObjects[id].setValue(val);
                }
            }
            catch (err){
                console.log("SSGeneric_draw Error"+generictype+" set value");
            }
        }
    });
}

function SSGeneric_repaint(ssid){
    $('.SSGeneric').each(function(index){
        var generictype =($(this).attr("generictype") === undefined) ? defaultInstrument() : $(this).attr("generictype");
        var id          = $(this).attr("id");
        if(id==ssid){
            var width = ($(this).width()<40)?40:$(this).width();
            var height = ($(this).height()<40)?40:$(this).height();


            var params= {
                width               : width,
                height              : height,
            };
            try {
                    SObjects[id].redraw();
            } catch (err){
                 console.log("SSGeneric_repaint Error"+generictype+" id: "+id+" set value");
            }
        }
    });
}

function SSGeneric_slowupdate(){
    //SSGeneric_draw();
}

function SSGeneric_fastupdate(){
    SSGeneric_draw();
}

/*
//TO DO
// Values, render only on change
//MIN MAX VALUES
//Single JS load for steelseries.js DONE
//linear scale lock?
*/
function setup_steelseries_object(elementclass){
    $('.'+elementclass).each(function(index){
        var id = "can-"+$(this).attr("id"); //Canvas
        var MinValue = ($(this).attr("MinValue")===undefined)? 0:$(this).attr("MinValue");
        var MaxValue = ($(this).attr("MaxValue")===undefined)? 100:$(this).attr("MaxValue");
        var title = ($(this).attr("title")===undefined)? "":$(this).attr("title");
        var units = ($(this).attr("unit")===undefined)? "":$(this).attr("unit");
        var threshold = ($(this).attr("threshold")===undefined)? 80:$(this).attr("threshold");

        var type = ($(this).attr("type")===undefined)? "TYPE4":$(this).attr("type");

        //set section colours :D
        var sections = [
            steelseries.Section(0, 25, 'rgba(0, 0, 220, 0.3)'),
            steelseries.Section(25, 50, 'rgba(0, 220, 0, 0.3)'),
            steelseries.Section(50, 75, 'rgba(220, 220, 0, 0.3)')
            ];

        // Define one area colour :P
        var areas = Array(steelseries.Section(75, 100, 'rgba(220, 0, 0, 0.3)'));

        // Start of SSGeneric
        if (elementclass=="SSGeneric"){
            if ($(this).attr("generictype") === undefined){
                $(this).attr("generictype", defaultInstrument());
            }

            //var lcdcolors       = new steelseries.LcdColor
            var generictype     =($(this).attr("generictype") === undefined) ? defaultInstrument() : $(this).attr("generictype");
            var myLcdColor      =($(this).attr("lcdcolor") === undefined) ? "STANDARD" : $(this).attr("lcdcolor");
            var lcdDecimals     =($(this).attr("lcddecimals") === undefined) ? 2 : $(this).attr("lcddecimals");
            lcdDecimals         =(lcdDecimals === ""||lcdDecimals>10||lcdDecimals<0) ? 2 : $(this).attr("lcdDecimals");
            var unitstring      =($(this).attr("unit") === undefined) ? "" : $(this).attr("unit");
            var unitstringbool  =(unitstring==="") ? false : true;
            var headerString    =($(this).attr("title") === undefined) ? "" : $(this).attr("title");
            var headerStringbool=(headerString==="") ? false : true;
            var pt   = ($(this).attr("pointertype")=== undefined)?"TYPE1":$(this).attr("pointertype");
            var pc   = ($(this).attr("pointercolour")=== undefined)?"RED":$(this).attr("pointercolour");
            var fgt  = ($(this).attr("ForegroundType")=== undefined)?"TYPE1":$(this).attr("ForegroundType");
            var frame= ($(this).attr("frame") === undefined) ? "METAL" : $(this).attr("frame");
            var bgc  = ($(this).attr("backgroundcolour") === undefined) ? "DARK_GRAY" : $(this).attr("backgroundcolour");

            var ssid = $(this).attr("id");
            var width = ($(this).width()<40)?40:$(this).width();
            var height = ($(this).height()<40)?40:$(this).height();


            var params= {
                width               : width,
                height              : height,
                unitStringVisible   : unitstringbool,
                unitString          : unitstring,
                headerString        : headerString,
                headerStringVisible : headerStringbool,
                valuesNumeric       : true,
                digitalFont         : true,
                lcdDecimals         : lcdDecimals,
                lcdVisible          : true,
                pointercolour       : pc,
                foregroundtype      : fgt,

                        };

            SObjects[ssid] = new steelseries[generictype](id, params);
            console.log(generictype);
            /*
            if(generictype=="Horizon"){
                console.log(generictype);
            }
            */
            switch (generictype){
                case "DisplaySingle":
                case "DisplayMulti":
                    SObjects[ssid].setLcdColor(steelseries.LcdColor[myLcdColor]);
                    break;
                case "LinearBargraph":
                case "RadialBargraph":
                    SObjects[ssid].setBackgroundColor(steelseries.BackgroundColor[bgc]);
                    SObjects[ssid].setFrameDesign(steelseries.FrameDesign[frame]);
                    SObjects[ssid].setLcdColor(steelseries.LcdColor[myLcdColor]);
                    break;
                case "trafficlight":
                    break;


                case "compass":
                    //SObjects[ssid].setPointerType(steelseries.PointerType[PointerType]);
                    SObjects[ssid].setPointerColor(steelseries.ColorDef[pc]);
                    SObjects[ssid].setForegroundType(steelseries.ForegroundType[fgt]);
                case "WindDirection":
                    SObjects[ssid].setPointerType(steelseries.PointerType[pt]);
                    //SObjects[ssid].setPointerColor(steelseries.ColorDef[pc]);

                case "altimeter":
                case "Altimeter":
                case "linear":
                case "RadialVertical":
                case "Radial":
                    SObjects[ssid].setLcdColor(steelseries.LcdColor[myLcdColor]);
                case "Clock":
                case "Level":
                case "StopWatch":
                    SObjects[ssid].setPointerColor(steelseries.ColorDef[pc]);
                    SObjects[ssid].setForegroundType(steelseries.ForegroundType[fgt]);
                    SObjects[ssid].setBackgroundColor(steelseries.BackgroundColor[bgc]);
                case "Horizon":
                    SObjects[ssid].setFrameDesign(steelseries.FrameDesign[frame]);
                    //radial1.setFrameDesign(steelseries.FrameDesign.STEEL);

            default:
                    break;
            }
            // fully redraw widget after parameters changed, and logf it if errors
            try {
                SObjects[ssid].redraw();
            }catch(Err){
                console.log("setup_steelseries_object Error"+generictype+" set value");

            }

        }
        // End of SSGeneric

    });
}