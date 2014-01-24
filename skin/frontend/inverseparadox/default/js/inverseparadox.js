var productDetailTabBreakpoint = 575;
var productDetailSingleColBreakpoint = 980;

/* === on dom load ================================================== */
$j(function() {


    /* === product list ============================================= */
    if ($j(window).width() <= productDetailSingleColBreakpoint) {
        $j('#filter-by-link').show();
        $j('.block-layered-nav').addClass('layered-nav-popup');
    }

    $j('#filter-by-link').click(function(e){
        e.preventDefault();
        if ($j('.block-layered-nav').hasClass('layered-nav-popup')) {
            $j('.block-layered-nav').addClass('open');
        }
    });
    $j('#filter-popup-close').click(function(e){
        e.preventDefault();
        $j(this).parents('.block-layered-nav').removeClass('open');
    });

    
    /* === product detail =========================================== */

    $j('#product-collateral-tabs .pages a').each(function(){
        $j(this).attr('href', $j(this).attr('href') + '#reviews');
    });

    $j('#product-collateral-tabs ul.tab-list a').click(function(e){
        e.preventDefault();
        $j('#product-collateral-tabs ul.tab-list a').removeClass('active');
        $j(this).addClass('active');
        $j('#product-collateral-tabs .box-collateral').removeClass('active');
        $j($j(this).attr('href')).addClass('active');
    });

    $j('#product-collateral-tabs .box-collateral-title').click(function(e){
        if ($j(window).width() <= productDetailTabBreakpoint) {
            e.preventDefault();
            $j(this).parents('.box-collateral').toggleClass('active');
        }
    });

    if (window.location.hash == '#reviews') {
        $j('#product-collateral-tabs ul.tab-list .reviews a').trigger('click');
        var tabs_offset = $j('#product-collateral-tabs').offset();
        $j('html, body').scrollTop(tabs_offset.top - 5);
    } else if (window.location.hash == '#add-review') {
        $j('#product-collateral-tabs ul.tab-list .reviews a').trigger('click');
    }

    $j('.product-shop .ratings a').click(function(e){
        e.preventDefault();
        if ($j(this).attr('href') == '#reviews') {
            $j('#product-collateral-tabs ul.tab-list .reviews a').trigger('click');
            var tabs_offset = $j('#product-collateral-tabs').offset();
            $j('html, body').scrollTop(tabs_offset.top - 5);
        } else if ($j(this).attr('href') == '#review-form') {
            $j('#product-collateral-tabs ul.tab-list .reviews a').trigger('click');
            var tabs_offset = $j('#review-form').offset();
            $j('html, body').scrollTop(tabs_offset.top - 5);
        }
    });

    $j('#product-thumbnails .product-thumbnail-link').click(function(e){
        e.preventDefault();
        var fullImg = $j(this).attr('href');
        var displayImg = $j(this).attr('data-swap-image');
        $j('#product-image-link').attr('href', fullImg);
        $j('#product-image').attr('src', displayImg);
        $j('#product-image').attr('data-zoom-image', fullImg);
        var zoom = $j('#product-image').data('elevateZoom');
        zoom.swaptheimage(displayImg, fullImg);
    });

    $j('#product-image-link').magnificPopup({
        type: 'image',
        midClick: true,
        closeOnContentClick: true
    });


    /* === checkout ================================================= */
    $j('#checkout-payment-method-load input').change(function(){
        $j('#checkout-payment-method-load dt').removeClass('selected');
        $j(this).parents('dt').addClass('selected');
    });


});


/* === on window resize ============================================= */
$j(window).resize(function() {


    /* === product list ============================================= */
    if ($j(window).width() <= productDetailSingleColBreakpoint) {
        $j('#filter-by-link').show();
        $j('.block-layered-nav').addClass('layered-nav-popup');
    } else {
        $j('#filter-by-link').hide();
        $j('.block-layered-nav').removeClass('layered-nav-popup');
    }


    /* === product detail =========================================== */
    if ($j(window).width() > productDetailTabBreakpoint) {
        $j('#product-collateral-tabs .box-collateral').removeClass('active');
        $j($j('#product-collateral-tabs ul.tab-list a.active').attr('href')).addClass('active');
    }

});