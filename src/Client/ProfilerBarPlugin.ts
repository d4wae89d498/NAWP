const $: JQueryStatic  = window["$"];

export function runPlugin() {
    $(() => {
        $(".toolbar > h1.logo").text("IPOLITIC::NAWP");
        $(".toolbar > nav > ul").append("<li><input type=\"checkbox\"> Disable javascript</li>" +
            "<li><input type=\"checkbox\"> Disable cookies</li>");
    });
}