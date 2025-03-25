/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

// "use strict";

document.addEventListener("DOMContentLoaded", function() {
    let loadingBar = document.getElementById("loadingBar");
    loadingBar.style.width = "100%";

    window.addEventListener("load", function() {
        setTimeout(() => {
            loadingBar.style.width = "0%";
        }, 500);
    });
});
