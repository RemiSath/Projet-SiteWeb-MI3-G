const themes = {
    default : "css/default.css",
    dark : "css/dark.css",
    contrast : "css/contrast.css",
    largeText : "css/largeText.css"
};

const validThemes = Object.keys(themes);
const themeLink = document.getElementById("theme-link");
const themeButton = document.getElementById("theme-button");

function getCookie(name){
    const cookies = document.cookie.split("; ");
    for(const cookie of cookies){
        const[key,value] = cookie.split("="); 
        if(key === name){
            return decodeURIComponent(value || "");
        }
    }
    return null;
}

function setCookie(name,value,days){
    const date = new Date();
    date.setTime(date.getTime() + days*24*60*60*1000);
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${date.toUTCString()}; path=/`;
}

function applyTheme(themeName){
    if(!validThemes.includes(themeName)){
        themeName = "default";
    }
    themeLink.href = themes[themeName];
    setCookie("theme",themeName,365);
}

document.addEventListener("DOMContentLoaded",() => {
    const savedTheme = getCookie("theme");
    if(savedTheme && validThemes.includes(savedTheme)){
        applyTheme(savedTheme);
    }
    else{
        applyTheme("default");
    }
});

themeButton.addEventListener("click",() => {
    const currentTheme = Object.keys(themes).find(key => themes[key] === themeLink.getAttribute("href")) || "default";
    const themeOrder = [
        "default",
        "dark",
        "contrast",
        "largeText"
    ];
    const currentIndex = themeOrder.indexOf(currentTheme);
    const nextTheme = themeOrder[(currentIndex + 1) % themeOrder.length];
    applyTheme(nextTheme);
})
