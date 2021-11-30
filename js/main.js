// Get markets price
function getMarkets(state = false) {
    $.post("bingbon.php", {state}, function(data, status){
        $(".table__body").html(data)
    });
}

// bot telegram element
const startBot = $(".tele__btn--start")
const stopBot =  $(".tele__btn--stop")
const teleDetail = $(".tele__detail")

// Start Bot Telegram
startBot.click(function() {
    $(this).addClass("tele__btn--clicked")
    stopBot.removeClass("tele__btn--clicked")
    teleDetail.html("Bot sedang memantau harga")
})

// Stop Bot Telegram
stopBot.click(function() {
    $(this).addClass("tele__btn--clicked")
    startBot.removeClass("tele__btn--clicked")
    teleDetail.html("Bot tidak mengirimkan pesan")
})

// Slider
const sliderTab = $(".slider__tab")
const sliderValue = $(".slider__value")

// nilai detik
let timer = sliderTab.val()

// Show initial value of slider
sliderValue.html(timer)

// Update value
sliderTab.on('input', function () {
    timer = sliderTab.val()
    sliderValue.html(timer)
})

sliderTab.on('mousemove', function () {
    const color = `linear-gradient(to right, #1cb81c ${((timer - 10) * 100) / (60 - 10)}%, #fff ${((timer - 10) * 100) / (60 - 10)}%)`
    $(this).css("background", color)
})

// Initial price
getMarkets()

// Interval for refresh asset prices
setInterval(() => {
    if (startBot.hasClass("tele__btn--clicked")){
        getMarkets(true)
    } else if (stopBot.hasClass("tele__btn--clicked")) {
        getMarkets()
    }
}, timer * 1000);