(function($) {
    if(typeof maps == "undefined") {
        maps= [];
    }

    config = [];
    $('.map_config').each(function(index,element) {
        config.push($.parseJSON(element.innerText));
    });

    data = $.parseJSON($('#map_data').text());
    max_cities = data["cities"].length;
    var settings = config[config.length-1];
    var pan = (settings["focus"] == 0) ? true : false;
    
    $('#'+settings["divName"]).css('background-image', 'url('+settings['board_url']+'/ext/consim/core/images/'+settings['map_name']+'.png)');

    for(i = 0; i < data["cities"].length; i++) {
        if(typeof data["language"]["BUILDING_TYPE_"+data["cities"][i]["type"]] != "undefined") {
            data["cities"][i]["name"] = data["language"]["BUILDING_TYPE_"+data["cities"][i]["type"]]+" "+data["cities"][i]["name"];
        }
    }
    maps.push(new jvm.Map({
        container: $('#'+settings["divName"]),
        map: settings["map_name"],
        zoomMax: 5,
        panOnDrag: pan,
        backgroundColor: 'transparent',
        zoomOnScroll: settings["zoom"],
        regionStyle:{
            initial: {
                fill: 'transparent',
                "fill-opacity": 0.5,
            },
            hover: {
                "fill-opacity": 0.7,
                cursor: 'pointer'
            }
        },
        onRegionTipShow: function(e, el, code){
            if(typeof data["roads"][code] != "undefined") {
                blocked = (data["roads"][code]["blocked"]==1) ? '<div class="blocked">'+data["language"]["ROAD_BLOCKED"]+'</div>' : 
                '<div class="not_blocked">'+data["language"]["ROAD_NOT_BLOCKED"]+'</div>';
                 el.html(data["roads"][code]["title"]+" ("+data["language"][data["roads"][code]["road_type"]]+")<br>"+blocked);
            }
            if(typeof data["provinces"][code] != "undefined") {
                 el.html(data["provinces"][code]["name"]+"<br>"+data["language"]["COUNTRY_"+data["provinces"][code]["country"]]);
            }
        },
        onMarkerTipShow: function(e, el, code){
            if(code > max_cities && typeof data["cities"][code-1] != "undefined") {
                 el.html(data["cities"][code-1]["name"]);
            }
        },
        markers: data["cities"].map(function(a) { return { name: a.name, coords: a.coords } }),       
        series: {
            regions: [{
                values: data["regions"].reduce(function(a,b){ a[b.id] = b.country; return a},{}),
                scale: {
                    1: "#7d2e29",
                    2: "#37532b",
                    3: "#1e1f19",
                    4: "#635846",
                    "HIGHLIGHT": "#E1C827",
                    "ROAD_TYPE_1": "#A68B5D", // Feldweg
                    "ROAD_TYPE_2": "#FAFAFA", // Befestigte Straße
                    "ROAD_TYPE_3": "#FFED70", // Schnellstraße
                    "ROAD_TYPE_4": "#000000", // Bahnschiene
                    "ROAD_TYPE_5": "#5C43D5", // Seeweg
                },
                attribute: 'fill'
            }],
            markers: [{
                attribute: 'image',
                scale: {
                "building_type_1": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_1.png',
                "building_type_2": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_2.png',
                "building_type_3": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_3.png',
                "building_type_4": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_4.png',
                "building_type_5": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_5.png',
                "building_type_6": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_6.png',
                "building_type_custom_1": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_custom_1.png',
                "building_type_custom_2": settings['board_url']+'/ext/consim/core/images/map_assets/building_type_custom_2.png',
                },
                values: data["cities"].reduce(function(a,b,c){ a[c] = "building_type_"+b.type;  return a}, {}),
                legend: {
                    horizontal: true,
                    title: data["language"]["MAP_LEGEND_TITLE"],
                    labelRender: function(v){
                    return {
                        building_type_1: data["language"]["BUILDING_TYPE_1"],
                        building_type_2: data["language"]["BUILDING_TYPE_2"],
                        building_type_3: data["language"]["BUILDING_TYPE_3"],
                        building_type_4: data["language"]["BUILDING_TYPE_4"],
                        building_type_5: data["language"]["BUILDING_TYPE_5"],
                        building_type_6: data["language"]["BUILDING_TYPE_6"],
                    }[v];
                    }
                }
            }]
        },
    }));

    if(!settings["legend"]) {
        $('#'+settings["divName"]).find(".jvectormap-legend").hide();
    }

    if(!settings["zoom"]) {
        $('#'+settings["divName"]).find(".jvectormap-zoomin").hide();
        $('#'+settings["divName"]).find(".jvectormap-zoomout").hide();
    }
    if(settings["focus"]!=0) {
        maps[maps.length-1].setFocus({region: settings["focus"]})
    }
})(jQuery);
