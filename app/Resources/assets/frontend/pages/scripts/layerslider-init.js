var LayersliderInit = function () {

    return {
        initLayerSlider: function () {
            $('#layerslider').layerSlider({
                skinsPath : '../../assets/global/plugins/slider-layer-slider/skins/',
                skin : 'fullwidth',
                thumbnailNavigation : 'hover',
                hoverPrevNext : false,
                responsive : false,
                responsiveUnder : 400,
                layersContainer : 400
            });
        }
    };

}();