! function(i) {
    "use strict";

    function t(t) {
        var e = "webkitAnimationEnd animationend";
        t.each(function() {
            var t = i(this),
                n = t.data("animation");
            t.addClass(n).one(e, function() {
                t.removeClass(n)
            })
        })
    }
    var e = i(".animate_text"),
        n = e.find(".item:first").find("[data-animation ^= 'animated']");
    e.carousel(), t(n), e.on("slide.bs.carousel", function(e) {
        var n = i(e.relatedTarget).find("[data-animation ^= 'animated']");
        t(n)
    }), i('[data-toggle="tooltip"]').tooltip(), i(".carousel-inner").swipe({
        swipeLeft: function(t, e, n, s, a) {
            i(this).parent().carousel("next")
        },
        swipeRight: function() {
            i(this).parent().carousel("prev")
        },
        threshold: 0
    }), i(".two_shows_one_move .item").each(function() {
        for (var t = i(this), e = 1; 2 > e; e++) t = t.next(), t.length || (t = i(this).siblings(":first")), t.children(":first-child").clone().addClass("cloneditem-" + e).appendTo(i(this))
    }), i(".four_shows_one_move .item").each(function() {
        for (var t = i(this), e = 1; 3 > e; e++) t = t.next(), t.length || (t = i(this).siblings(":first")), t.children(":first-child").clone().addClass("cloneditem-" + e).appendTo(i(this))
	}), i(".four_shows_one_move_big .item").each(function() {
        for (var t = i(this), e = 1; 4 > e; e++) t = t.next(), t.length || (t = i(this).siblings(":first")), t.children(":first-child").clone().addClass("cloneditem-" + e).appendTo(i(this))
    }), i(".three_shows_one_move .item").each(function() {
        for (var t = i(this), e = 1; 4 > e; e++) t = t.next(), t.length || (t = i(this).siblings(":first")), t.children(":first-child").clone().addClass("cloneditem-" + e).appendTo(i(this))
    }), i(".4_shows_one_move .item").each(function() {
        for (var t = i(this), e = 1; 4 > e; e++) t = t.next(), t.length || (t = i(this).siblings(":first")), t.children(":first-child").clone().addClass("cloneditem-" + e).appendTo(i(this))
    }), i(".five_shows_one_move .item").each(function() {
        for (var t = i(this), e = 1; 5 > e; e++) t = t.next(), t.length || (t = i(this).siblings(":first")), t.children(":first-child").clone().addClass("cloneditem-" + e).appendTo(i(this))
    }),i(".six_shows_one_move .item").each(function() {
        for (var t = i(this), e = 1; 6 > e; e++) t = t.next(), t.length || (t = i(this).siblings(":first")), t.children(":first-child").clone().addClass("cloneditem-" + e).appendTo(i(this))
    })
}(jQuery);