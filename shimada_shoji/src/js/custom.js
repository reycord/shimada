/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split("#")[0].split("?")[0],
    $BODY = $("body"),
    $MENU_TOGGLE = $("#menu_toggle"),
    $SIDEBAR_MENU = $("#sidebar-menu"),
    $SIDEBAR_FOOTER = $(".sidebar-footer"),
    $LEFT_COL = $(".left_col"),
    $RIGHT_COL = $(".right_col"),
    $NAV_MENU = $(".nav_menu"),
    $FOOTER = $("footer");
    $NAV_TITLE = $(".nav_title")
var itemsList = null;

// Sidebar
$(document).ready(function() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function() {
        // reset height
        $RIGHT_COL.css("min-height", $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass("footer_fixed") ? -10 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css("min-height", contentHeight);
    };

    $SIDEBAR_MENU.find("a").on("click", function(ev) {
        var $li = $(this).parent();

        if ($li.is(".active")) {
            $li.removeClass("active active-sm");
            $("ul:first", $li).slideUp(function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is(".child_menu")) {
                $SIDEBAR_MENU.find("li").removeClass("active active-sm");
                $SIDEBAR_MENU.find("li ul").slideUp();
            }

            $li.addClass("active");

            $("ul:first", $li).slideDown(function() {
                setContentHeight();
            });
        }

        $.ajax({
            url: base_url + "users/setBodyClass",
            data: {"bodyClass": $BODY.attr('class')},
            success: function(response) {
            }
        });
    });

    $NAV_TITLE.find("a").on("click", function(){
        $.ajax({
            url: base_url + "users/setBodyClass",
            data: {"bodyClass": $BODY.attr('class')},
            success: function(response) {
            }
        });
    });

    // toggle small or large menu
    $MENU_TOGGLE.on("click", function() {
        if ($BODY.hasClass("nav-md")) {
            $SIDEBAR_MENU.find("li.active ul").hide();
            $SIDEBAR_MENU
                .find("li.active")
                .addClass("active-sm")
                .removeClass("active");
        } else {
            $SIDEBAR_MENU.find("li.active-sm ul").show();
            $SIDEBAR_MENU
                .find("li.active-sm")
                .addClass("active")
                .removeClass("active-sm");
        }

        $BODY.toggleClass("nav-md nav-sm");

        setContentHeight();

        $(".datatable").each(function() {
            $(this)
                .dataTable()
                .fnDraw();
        });
    });
    // check active menu
    $SIDEBAR_MENU
        .find('a[href="' + CURRENT_URL + '"]')
        .parent("li")
        .addClass("current-page");

    // $SIDEBAR_MENU
    //     .find("a")
    //     .filter(function() {
    //         return this.href == CURRENT_URL;
    //     })
    //     .parent("li")
    //     .addClass("current-page")
    //     .parents("ul")
    //     .slideDown(function() {
    //         setContentHeight();
    //     })
    //     .parent()
    //     .addClass("active");

    $SIDEBAR_MENU
        .find("a")
        .filter(function() {
            return this.href.indexOf(controllerName || ' ') >= 0;
        })
        .parent("li")
        .parents("ul")
        .slideDown(function() {
            setContentHeight();
        })
        .parent()
        .addClass("active");

    // recompute content when resizing
    // $(window).smartresize(function() {
    //     setContentHeight();
    // });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $(".menu_fixed").mCustomScrollbar({
            autoHideScrollbar: true,
            theme: "minimal",
            mouseWheel: {
                preventDefault: true
            }
        });
    }
});
// /Sidebar

// Panel toolbox
$(document).ready(function() {
    $(".collapse-link").on("click", function() {
        var $BOX_PANEL = $(this).closest(".x_panel"),
            $ICON = $(this).find("i"),
            $BOX_CONTENT = $BOX_PANEL.find(".x_content");

        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr("style")) {
            $BOX_CONTENT.slideToggle(200, function() {
                $BOX_PANEL.removeAttr("style");
            });
        } else {
            $BOX_CONTENT.slideToggle(200);
            $BOX_PANEL.css("height", "auto");
        }

        $ICON.toggleClass("fa-chevron-up fa-chevron-down");
    });

    $(".close-link").click(function() {
        var $BOX_PANEL = $(this).closest(".x_panel");

        $BOX_PANEL.remove();
    });
});
// /Panel toolbox

// Tooltip
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: "body"
    });
});
// /Tooltip

// Progressbar
$(document).ready(function() {
    if ($(".progress .progress-bar")[0]) {
        $(".progress .progress-bar").progressbar();
    }
});
// /Progressbar

// Switchery
$(document).ready(function() {
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(
            document.querySelectorAll(".js-switch")
        );
        elems.forEach(function(html) {
            var switchery = new Switchery(html, {
                color: "#26B99A"
            });
        });
    }
});
// /Switchery

// iCheck
$(document).ready(function() {
    if ($("input.flat")[0]) {
        $(document).ready(function() {
            $("input.flat").iCheck({
                checkboxClass: "icheckbox_flat-green",
                radioClass: "iradio_flat-green"
            });
        });
    }
});
// /iCheck

// Table
$("table input").on("ifChecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .addClass("selected");
    countChecked();
});
$("table input").on("ifUnchecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .removeClass("selected");
    countChecked();
});

var checkState = "";

$(".bulk_action input").on("ifChecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .addClass("selected");
    countChecked();
});
$(".bulk_action input").on("ifUnchecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .removeClass("selected");
    countChecked();
});
$(".bulk_action input#check-all").on("ifChecked", function() {
    checkState = "all";
    countChecked();
});
$(".bulk_action input#check-all").on("ifUnchecked", function() {
    checkState = "none";
    countChecked();
});

function countChecked() {
    if (checkState === "all") {
        $(".bulk_action input[name='table_records']").iCheck("check");
    }
    if (checkState === "none") {
        $(".bulk_action input[name='table_records']").iCheck("uncheck");
    }

    var checkCount = $(".bulk_action input[name='table_records']:checked").length;

    if (checkCount) {
        $(".column-title").hide();
        $(".bulk-actions").show();
        $(".action-cnt").html(checkCount + " Records Selected");
    } else {
        $(".column-title").show();
        $(".bulk-actions").hide();
    }
}

// Accordion
$(document).ready(function() {
    $(".expand").on("click", function() {
        $(this)
            .next()
            .slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

// NProgress
if (typeof NProgress != "undefined") {
    $(document).ready(function() {
        NProgress.start();
    });

    $(window).on("load", function() {
        NProgress.done();
    });
}
// Accordion
$(document).ready(function() {
    $(".datatable").each(function(idx, element) {
        var dataTB = $(element).dataTable({
            oLanguage: {
                sSearch: "Filter: "
            },
            "scrollX": true,
            // aaSorting: []
            ordering: false
        });
        if ($(element).attr('data-modal')) {

            $($(element).attr('data-modal')).on('shown.bs.modal', function() {
                dataTB.fnDraw();
            })

        }
    });
    $(".date:not([readonly])").datepicker({
        todayHighlight: true,
        format: "d M, yyyy",
        autoclose: true
    });
    // $('#items_orders_list').dataTable({
    //     "oLanguage": {
    //         "sSearch": "Filter: "
    //     },
    //     "bFilter": false
    // });
});
var theme = {
    color: [
        '#0f4f8e', '#1fb8c5', '#d47300', '#00d40e',
        '#ff0000', '#7366ea', '#9a613b', '#757575',
        '#0097ff', '#fff400'
    ],

    title: {
        itemGap: 8,
        textStyle: {
            fontWeight: 'normal',
            color: '#408829'
        }
    },

    dataRange: {
        color: ['#1f610a', '#97b58d']
    },

    toolbox: {
        color: ['#408829', '#408829', '#408829', '#408829']
    },

    tooltip: {
        backgroundColor: 'rgba(0,0,0,0.5)',
        axisPointer: {
            type: 'line',
            lineStyle: {
                color: '#408829',
                type: 'dashed'
            },
            crossStyle: {
                color: '#408829'
            },
            shadowStyle: {
                color: 'rgba(200,200,200,0.3)'
            }
        }
    },

    dataZoom: {
        dataBackgroundColor: '#eee',
        fillerColor: 'rgba(64,136,41,0.2)',
        handleColor: '#408829'
    },
    grid: {
        borderWidth: 0
    },

    categoryAxis: {
        axisLine: {
            lineStyle: {
                color: '#408829'
            }
        },
        splitLine: {
            lineStyle: {
                color: ['#eee']
            }
        }
    },

    valueAxis: {
        axisLine: {
            lineStyle: {
                color: '#408829'
            }
        },
        splitArea: {
            show: true,
            areaStyle: {
                color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
            }
        },
        splitLine: {
            lineStyle: {
                color: ['#eee']
            }
        }
    },
    timeline: {
        lineStyle: {
            color: '#408829'
        },
        controlStyle: {
            normal: { color: '#408829' },
            emphasis: { color: '#408829' }
        }
    },

    k: {
        itemStyle: {
            normal: {
                color: '#68a54a',
                color0: '#a9cba2',
                lineStyle: {
                    width: 1,
                    color: '#408829',
                    color0: '#86b379'
                }
            }
        }
    },
    map: {
        itemStyle: {
            normal: {
                areaStyle: {
                    color: '#ddd'
                },
                label: {
                    textStyle: {
                        color: '#c12e34'
                    }
                }
            },
            emphasis: {
                areaStyle: {
                    color: '#99d2dd'
                },
                label: {
                    textStyle: {
                        color: '#c12e34'
                    }
                }
            }
        }
    },
    force: {
        itemStyle: {
            normal: {
                linkStyle: {
                    strokeColor: '#408829'
                }
            }
        }
    },
    chord: {
        padding: 4,
        itemStyle: {
            normal: {
                lineStyle: {
                    width: 1,
                    color: 'rgba(128, 128, 128, 0.5)'
                },
                chordStyle: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    }
                }
            },
            emphasis: {
                lineStyle: {
                    width: 1,
                    color: 'rgba(128, 128, 128, 0.5)'
                },
                chordStyle: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    }
                }
            }
        }
    },
    gauge: {
        startAngle: 225,
        endAngle: -45,
        axisLine: {
            show: true,
            lineStyle: {
                color: [
                    [0.2, '#86b379'],
                    [0.8, '#68a54a'],
                    [1, '#408829']
                ],
                width: 8
            }
        },
        axisTick: {
            splitNumber: 10,
            length: 12,
            lineStyle: {
                color: 'auto'
            }
        },
        axisLabel: {
            textStyle: {
                color: 'auto'
            }
        },
        splitLine: {
            length: 18,
            lineStyle: {
                color: 'auto'
            }
        },
        pointer: {
            length: '90%',
            color: 'auto'
        },
        title: {
            textStyle: {
                color: '#333'
            }
        },
        detail: {
            textStyle: {
                color: 'auto'
            }
        }
    },
    textStyle: {
        fontFamily: 'Arial, Verdana, sans-serif'
    }
};
if ($('#echart_pie').length) {

    var echartPie = echarts.init(document.getElementById('echart_pie'), theme);

    echartPie.setOption({
        title: {
            // text: 'Sales Composition',
            text: lang['sales_composition'],
            x: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            type: 'scroll',
            orient: 'vertical',
            right: 10,
            top: 20,
            bottom: 20,
            data: ['Salesman1', 'Salesman2', 'Salesman3', 'Salesman4', 'Salesman5', 'Salesman6', 'Salesman7', 'Salesman8', 'Salesman9', 'Others']
        },
        toolbox: {
            show: true,
            feature: {
                magicType: {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore: {
                    show: false,
                    title: "Restore"
                },
                saveAsImage: {
                    show: false,
                    title: "Save Image"
                }
            }
        },
        calculable: true,
        series: [{
            name: 'Sales Composition',
            type: 'pie',
            radius: '50%',
            center: ['38%', '50%'],
            data: [{
                value: 335,
                name: 'Salesman1'
            }, {
                value: 310,
                name: 'Salesman2'
            }, {
                value: 234,
                name: 'Salesman3'
            }, {
                value: 135,
                name: 'Salesman4'
            }, {
                value: 1548,
                name: 'Salesman5'
            }, {
                value: 711,
                name: 'Salesman6'
            }, {
                value: 844,
                name: 'Salesman7'
            }, {
                value: 148,
                name: 'Salesman8'
            }, {
                value: 1148,
                name: 'Salesman9'
            }, {
                value: 674,
                name: 'Others'
            }]
        }]
    });

    var dataStyle = {
        normal: {
            label: {
                show: false
            },
            labelLine: {
                show: false
            }
        }
    };

    var placeHolderStyle = {
        normal: {
            color: 'rgba(0,0,0,0)',
            label: {
                show: false
            },
            labelLine: {
                show: false
            }
        },
        emphasis: {
            color: 'rgba(0,0,0,0)'
        }
    };

}

// Bar chart

if ($("#mybarChart").length) {
    var ctx = document.getElementById("mybarChart");
    var mybarChart = new Chart(ctx, {
        type: "bar",
        data: {
            // labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            labels: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            datasets: [{
                label: "# Sales",
                backgroundColor: "#26B99A",
                data: [51, 30, 40, 28, 92, 50, 45, 21, 35, 46, 39, 69]
            }]
        },

        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}
// enable edit content
var historyClick = [];
// enable edit content
var historyValue = [];

// function updateItem(line, objButton) {
//     var idx = historyClick.indexOf(line);
//     var ele = $(
//         "#items_orders_list tbody tr:nth-child(" + line + ") td:nth-child(4) div"
//     );
//     var quantity = parseInt($(ele).text());
//     if (idx !== -1) {
//         var differVal = quantity - historyValue[idx];
//         var itemId = $(
//             "#items_orders_list tbody tr:nth-child(" + line + ") td:nth-child(2)"
//         ).text();
//         console.log(differVal, itemId);
//         updateItemquantity(itemId, differVal);
//         $(ele)
//             .attr("contenteditable", false)
//             .parent()
//             .removeClass("content-edit");
//         $("#add").removeAttr("disabled");
//         $(objButton).html('<span class="glyphicon glyphicon-edit"></span>');
//         historyClick.splice(idx, 1);
//         historyValue.splice(idx, 1);
//         return;
//     }
//     $(ele)
//         .attr("contenteditable", true)
//         .parent()
//         .addClass("content-edit");
//     $(objButton).html('<span class="glyphicon glyphicon-check"></span>');
//     placeCaretAtEnd($(ele).get(0));
//     $(ele).attr("onkeypress", "return isNumberKey(event)");
//     $(ele).attr("oninput", "return maxLengthDivCheck(this)");
//     $("#add").attr("disabled", "disabled");
//     historyClick.push(line);
//     historyValue.push(quantity);
// }
//focus to last character
function placeCaretAtEnd(el) {
    el.focus();
    if (
        typeof window.getSelection != "undefined" &&
        typeof document.createRange != "undefined"
    ) {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }
}
//Check maxlength for  number
function maxLengthDivCheck(object) {
    var max = $(object).attr("maxLength");
    max = max != undefined && max > 0 ? max : 6;
    if ($(object).text().length > max)
        $(object).text(
            $(object)
            .text()
            .slice(0, max)
        );
    placeCaretAtEnd($(object).get(0));
}
//Check maxlength for input number
function maxLengthCheck(object) {
    if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength);
}

// validate input type number
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
}
//config choose file
$("#choose_file").on("click", function() {
    $("#file_upload_hidden").trigger("click");
});
$("input[type=file]#file_upload_hidden").change(function() {
    var pathFile = this.value.replace(/^.*[\\\/]/, '');
    $("#file_upload").val(pathFile);
    PreviewPdf();
});
//preview filepdf
function PreviewPdf() {
    pdffile = document.getElementById("file_upload_hidden").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#pre_file_upload').attr('src', pdffile_url);
}
// send post request function
function sendPostRequest(url, bodyData) {
    console.log(url);
    var res = null;
    $.ajax({
        url: url,
        type: "POST",
        data: bodyData,
        async: false,
        success: function(response) {
            console.log(response);
            res = response;
        },
        error: function(error) {
            console.log(error);
        }
    });
    return res;
}
// send get request function
function sendGetRequest(url, bodyData) {
    console.log(url);
    $.ajax({
        url: url,
        type: "GET",
        data: bodyData,
        success: function(response) {
            console.log(response);
        },
        error: function(xhr) {
            console.log(xhr);
        }
    });
}

// search object in array
function searchItems(nameKey, myArray) {
    for (var i = 0; i < myArray.length; i++) {
        if (myArray[i].item_no === nameKey) {
            return myArray[i];
        }
    }
}

$(document).ready(function() {
    $("#order").focus();
});
$('.has-clear input[type="text"]:not([readonly])').on('input propertychange change', function() {
    var $this = $(this);
    var visible = Boolean($this.val());
    $this.siblings('.form-control-clear').toggleClass('hidden', !visible);
}).trigger('propertychange');

$(document).on("click", '.form-control-clear', function() {
    $(this).siblings('input[type="text"]').val('')
        .trigger('propertychange').focus();
});

function btn_add_delivery() {
    var count = $('div.group-delivery:last').attr('id');
    count = Number(count);
    var count_group_delivery = $('.group-delivery').length + 1;
    delivery_number = count + 1;
    // alert(delivery_number);
    // return;
    // alert('asd');
    var html = "<div class='form-group'>" +
        "<div class='col-sm-12 no-padding-left'>" +
        "<label class='control-label col-sm-2 form-title' for='delivery_to' ></label>" +
        "<div class='col-sm-2'>" +
        "<label class='control-label form-title' for='delivery' style='margin-left: -3px;'>Delivery " + count_group_delivery + "</label>" +
        "</div>" +
        "<div class='col-sm-8 no-padding-right'>" +
        "<select class='form-control' id='delivery_to' name='delivery_to'>" +
        "<option value=''></option>" +
        "</select>" +
        "<button type='button' onclick='delete_delivery(this)' class='btn btn-delete-delivery ' id='delivery" + count + "'><i class='fa fa-trash'></i></button>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "<div class='form-group'>" +
        "<div class='col-sm-12 no-padding-left'>" +
        "<label class='control-label col-sm-2 form-title' for='delivery_to' ></label>" +
        "<div class='col-sm-2'>" +
        "<label class='control-label form-title' for='delivery' style='margin-left: -3px;'>Delivery Address</label>" +
        "</div>" +
        "<div class='col-sm-8 no-padding-right'>" +
        "<textarea class='form-control form-rounded' rows='2' id='delivery_address' name='delivery_address' ></textarea>" +
        "</div>" +
        "</div>" +
        "</div>";
    var group_delivery_new = "<div id='" + delivery_number + "' class='group-delivery'" +
        "</div>";
    $("#" + count + "").append(html);
    $("#" + count + "").after(group_delivery_new);
}

function delete_delivery(event) {
    var count = $(event).attr('id');
    var res = count.substring(8, count.length);
    $("#" + res + "").remove();
}

// First, checks if it isn't implemented yet.
// Example: "{0} is dead, but {1} is alive! {0} {2}".format("ASP", "ASP.NET")
if (!String.prototype.format) {
    String.prototype.format = function() {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function(match, number) {
            return typeof args[number] != 'undefined' ?
                args[number] :
                match;
        });
    };
}
if (!String.prototype.escape) {
    String.prototype.escape = function() {
        var map = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            "\"": "&quot;",
            "'": "&#39;" // ' -> &apos; for XML only
        };
        return this.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
}
if (!String.prototype.hashCode) {
    String.prototype.hashCode = function() {
        // var hash = 0, i, chr;
        // if (this.length === 0) return hash;
        // for (i = 0; i < this.length; i++) {
        //   chr   = this.charCodeAt(i);
        //   hash  = ((hash << 5) - hash) + chr;
        //   hash |= 0; // Convert to 32bit integer
        // }
        hash = this.replace(/[ /]/g,'-');
        return hash;
      };
}

function snackbarShow(message, timeshow) {
    if (!message) return;
    if (!timeshow || isNaN(timeshow)) timeshow = 3000;

    $('#snackbar').html(message).addClass("show-message");
    clearTimeout(snackbarTimeout);
    var snackbarTimeout = setTimeout(function() {
        $('#snackbar').removeClass("show-message");
    }, timeshow);
}

function numberWithCommas(nStr) {
    // if (!$.isNumeric(x)) return 0;
    // x = parseFloat(x);
    // return x.toString().replace(/\B(?=(\d{3}.)+(?!\d))/g, ",");
    nStr = parseFloat(nStr);
    if(!nStr) return 0;
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
// Returns a string representing a number in fixed-point notation via currency.
Number.prototype.myToFixed = function(currency) {
    var fractionDigits = 4;
    var outputCurrency = currency.trim();
    if (outputCurrency == 'JPY') {
        fractionDigits = 2;
    }
    var x = Number(this||0);
    var num = x.toString().split(".");
    if(num[1]){
        var v = num[1];
        if (outputCurrency == 'VND') {
            var result = Number(num[0]);
            if(Number(v.substr(0, (1))) >= 5){
                return (++result);
            } else {
                return result;
            }
        }
        if (v.length > fractionDigits){
            v = v.substr(0, (fractionDigits));
            return (num[0]+'.'+v);
        } else {
            x = x.toFixed(fractionDigits);
        }
    } else {
        if(outputCurrency == 'VND') { 
            return x;
        } else {
            x = x.toFixed(fractionDigits);
        }
    }
    return x;
};

function formatDate(date) {
    if(date == null || date == ''){
        return '';
    }
    var date = new Date(date);
    var monthNames = [
        "Jan", "Feb", "Mar",
        "Apr", "May", "Jun", "Jul",
        "Aug", "Sep", "Oct",
        "Nov", "Dec"
    ];

    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();

    return day + ' ' + monthNames[monthIndex] + ', ' + year;
}

function checkQuantity(e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}
$('.text-uppercase').keyup(function() {
    this.value = this.value.toUpperCase();
});
// $(".has-clear :input:not([readonly])").append('<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>');

function moveCursorToEnd(input) {
    var originalValue = input.val();
    input.val('');
    input.blur().focus().val(originalValue);
}
// Polyfill Object.assign
if (typeof Object.assign != 'function') {
    // Must be writable: true, enumerable: false, configurable: true
    Object.defineProperty(Object, "assign", {
      value: function assign(target, varArgs) { // .length of function is 2
        'use strict';
        if (target == null) { // TypeError if undefined or null
          throw new TypeError('Cannot  convert undefined or null to object');
        }
  
        var to = Object(target);
  
        for (var index = 1; index < arguments.length; index++) {
          var nextSource = arguments[index];
  
          if (nextSource != null) { // Skip over if undefined or null
            for (var nextKey in nextSource) {
              // Avoid bugs when hasOwnProperty is shadowed
              if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                to[nextKey] = nextSource[nextKey];
              }
            }
          }
        }
        return to;
      },
      writable: true,
      configurable: true
    });
  }

// Override parseFloat function because handle NaN result of parseFloat
var origParseFloat = parseFloat;
parseFloat = function(str) {
    return origParseFloat(str)||0;
}