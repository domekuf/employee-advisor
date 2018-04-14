function starRateRefresh() {
    var currentRate = parseInt($("#review-rate").val());
    $(".fa-star").each(function(){
        if (currentRate) {
            $(this).removeClass("far").addClass("fas");
            currentRate --;
            console.log(currentRate);
        } else {
            $(this).removeClass("fas").addClass("far");
        }
    });
}

$(document).ready(function() {
    var newRate;
    $(".fa-star").mouseenter(function(e) {
        var $this = $(this);
        newRate = 0;
        while ($this.hasClass("fa-star")) {
            newRate ++;
            $this.removeClass("far").addClass("fas");
            $this = $this.prev();
        }; 
    }).mouseleave(function(e) {
        starRateRefresh();
    }).click(function(e) {
        $("#review-rate").val(newRate);
        starRateRefresh();
    });
});
