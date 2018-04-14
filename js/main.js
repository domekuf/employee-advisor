function employeeAdvisor(main) {
    if (!$) {
        console.log("jQuery not defined");
        return;
    }
    this._view = function(content) {
        $(main).html(content);
    }
    this._pages = {};
    this.PageRegister = function(name, page) {
        if (!page) {
            console.error("Error in page "+name+": Object undefined.");
            return;
        }
        if (!page["view"]) {
            console.error("Error in page "+name+": Object.view undefined");
            return;
        }
        this._pages[name] = page;
    }
    this.PageLoad = function(name) {
        const _this = this;
        $.ajax({
            url : "/view/" + _this._pages[name]["view"] + "?d=" + (new Date()).getTime()
        }).done(function(res) {
            _this._view(res);
            $(".callback").each(function(){
                /*
                const __this = this;
                const api = $(this).attr("api");
                const template = $(this).find(".template");
                if (template.length > 1) {
                    console.warn("More than one template in this callback");
                }
                const target = $(this).find(".target");
                if (target.length > 1) {
                    console.warn("More than one target in this callback");
                }
                $.ajax({
                    url: "/api.php/" + api
                }).done(function(_res){
                    var html = "";
                    for (i in _res) {
                        var _html = template.html();
                        for (j in _res[i]) {
                            _html = _html.replace("["+j+"]", _res[i][j]);
                            console.log("_res[i]", _res[i]);
                            console.log(_html);
                        }
                        html += _html;
                    }
                    target.html(html);
                });
                */
            });
            $("form.ajax").on("submit", function(e) {
                e.preventDefault();
                console.log($(this).find("input"));
                const page = $(this).attr("action");
                if (!page) {
                    console.error("This form has no action attribute");
                    return;
                }
                if (!_this._pages[page]) {
                    console.error("Page "+page+" doesn't exists");
                    return;
                }
                _this.PageLoad(page);
                return;
            });
        });
    }
}
/*
var eA = new employeeAdvisor("#main");
eA.PageRegister("login", {"view": "login.html"});
eA.PageRegister("review", {"view": "review.php"});

$(document).ready(function() {
    eA.PageLoad("login");
});
*/

