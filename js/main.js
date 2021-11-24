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

// Page first opened
getMarkets()

// Interval for 2 sec
setInterval(() => {
    if (startBot.hasClass("tele__btn--clicked")){
        getMarkets(true)
    } else if (stopBot.hasClass("tele__btn--clicked")) {
        getMarkets()
    }
}, 2000);