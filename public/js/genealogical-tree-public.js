! function($) {
  "use strict";
  var Tooltip = function(element, options) {
    this.init('tooltip', element, options)
  }
  Tooltip.prototype = {
    constructor: Tooltip,
    init: function(type, element, options) {
      var eventIn, eventOut, triggers, trigger, i
      this.type = type
      this.$element = $(element)
      this.options = this.getOptions(options)
      this.enabled = true
      triggers = this.options.trigger.split(' ')
      for (i = triggers.length; i--;) {
        trigger = triggers[i]
        if (trigger == 'click') {
          this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
        } else if (trigger != 'manual') {
          eventIn = trigger == 'hover' ? 'mouseenter' : 'focus'
          eventOut = trigger == 'hover' ? 'mouseleave' : 'blur'
          this.$element.on(eventIn + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
          this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
        }
      }
      this.options.selector ? (this._options = $.extend({}, this.options, {
        trigger: 'manual',
        selector: ''
      })) : this.fixTitle()
    },
    getOptions: function(options) {
      options = $.extend({}, $.fn[this.type].defaults, this.$element.data(), options)
      if (options.delay && typeof options.delay == 'number') {
        options.delay = {
          show: options.delay,
          hide: options.delay
        }
      }
      return options
    },
    enter: function(e) {
      var self = $(e.currentTarget)[this.type](this._options).data(this.type)
      if (!self.options.delay || !self.options.delay.show) return self.show()
      clearTimeout(this.timeout)
      self.hoverState = 'in'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'in') self.show()
      }, self.options.delay.show)
    },
    leave: function(e) {
      var self = $(e.currentTarget)[this.type](this._options).data(this.type)
      if (this.timeout) clearTimeout(this.timeout)
      if (!self.options.delay || !self.options.delay.hide) return self.hide()
      self.hoverState = 'out'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'out') self.hide()
      }, self.options.delay.hide)
    },
    show: function() {
      var $tip, pos, actualWidth, actualHeight, placement, tp, e = $.Event('show')
      if (this.hasContent() && this.enabled) {
        this.$element.trigger(e)
        if (e.isDefaultPrevented()) return
        $tip = this.tip()
        this.setContent()
        if (this.options.animation) {
          $tip.addClass('fade')
        }
        placement = typeof this.options.placement == 'function' ? this.options.placement.call(this, $tip[0], this.$element[0]) : this.options.placement
        $tip.detach().css({
          top: 0,
          left: 0,
          display: 'block'
        })
        this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)
        pos = this.getPosition()
        actualWidth = $tip[0].offsetWidth
        actualHeight = $tip[0].offsetHeight
        switch (placement) {
          case 'bottom':
            tp = {
              top: pos.top + pos.height,
              left: pos.left + pos.width / 2 - actualWidth / 2
            }
            break
          case 'top':
            tp = {
              top: pos.top - actualHeight,
              left: pos.left + pos.width / 2 - actualWidth / 2
            }
            break
          case 'left':
            tp = {
              top: pos.top + pos.height / 2 - actualHeight / 2,
              left: pos.left - actualWidth
            }
            break
          case 'right':
            tp = {
              top: pos.top + pos.height / 2 - actualHeight / 2,
              left: pos.left + pos.width
            }
            break
        }
        this.applyPlacement(tp, placement)
        this.$element.trigger('shown')
      }
    },
    applyPlacement: function(offset, placement) {
      var $tip = this.tip(),
        width = $tip[0].offsetWidth,
        height = $tip[0].offsetHeight,
        actualWidth, actualHeight, delta, replace
      $tip.offset(offset).addClass(placement).addClass('in')
      actualWidth = $tip[0].offsetWidth
      actualHeight = $tip[0].offsetHeight
      if (placement == 'top' && actualHeight != height) {
        offset.top = offset.top + height - actualHeight
        replace = true
      }
      if (placement == 'bottom' || placement == 'top') {
        delta = 0
        if (offset.left < 0) {
          delta = offset.left * -2
          offset.left = 0
          $tip.offset(offset)
          actualWidth = $tip[0].offsetWidth
          actualHeight = $tip[0].offsetHeight
        }
        this.replaceArrow(delta - width + actualWidth, actualWidth, 'left')
      } else {
        this.replaceArrow(actualHeight - height, actualHeight, 'top')
      }
      if (replace) $tip.offset(offset)
    },
    replaceArrow: function(delta, dimension, position) {
      this.arrow().css(position, delta ? (50 * (1 - delta / dimension) + "%") : '')
    },
    setContent: function() {
      var $tip = this.tip(),
        title = this.getTitle()
      $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
      $tip.removeClass('fade in top bottom left right')
    },
    hide: function() {
      var that = this,
        $tip = this.tip(),
        e = $.Event('hide')
      this.$element.trigger(e)
      if (e.isDefaultPrevented()) return
      $tip.removeClass('in')

      function removeWithAnimation() {
        var timeout = setTimeout(function() {
          $tip.off($.support.transition.end).detach()
        }, 500)
        $tip.one($.support.transition.end, function() {
          clearTimeout(timeout)
          $tip.detach()
        })
      }
      $.support.transition && this.$tip.hasClass('fade') ? removeWithAnimation() : $tip.detach()
      this.$element.trigger('hidden')
      return this
    },
    fixTitle: function() {
      var $e = this.$element
      if ($e.attr('title') || typeof($e.attr('data-original-title')) != 'string') {
        $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
      }
    },
    hasContent: function() {
      return this.getTitle()
    },
    getPosition: function() {
      var el = this.$element[0]
      return $.extend({}, (typeof el.getBoundingClientRect == 'function') ? el.getBoundingClientRect() : {
        width: el.offsetWidth,
        height: el.offsetHeight
      }, this.$element.offset())
    },
    getTitle: function() {
      var title, $e = this.$element,
        o = this.options
      title = $e.attr('data-original-title') || (typeof o.title == 'function' ? o.title.call($e[0]) : o.title)
      return title
    },
    tip: function() {
      return this.$tip = this.$tip || $(this.options.template)
    },
    arrow: function() {
      return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    },
    validate: function() {
      if (!this.$element[0].parentNode) {
        this.hide()
        this.$element = null
        this.options = null
      }
    },
    enable: function() {
      this.enabled = true
    },
    disable: function() {
      this.enabled = false
    },
    toggleEnabled: function() {
      this.enabled = !this.enabled
    },
    toggle: function(e) {
      var self = e ? $(e.currentTarget)[this.type](this._options).data(this.type) : this
      self.tip().hasClass('in') ? self.hide() : self.show()
    },
    destroy: function() {
      this.hide().$element.off('.' + this.type).removeData(this.type)
    }
  }
  /* TOOLTIP PLUGIN DEFINITION
   * ========================= */
  var old = $.fn.tooltip
  $.fn.tooltip = function(option) {
    return this.each(function() {
      var $this = $(this),
        data = $this.data('tooltip'),
        options = typeof option == 'object' && option
      if (!data) $this.data('tooltip', (data = new Tooltip(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }
  $.fn.tooltip.Constructor = Tooltip
  $.fn.tooltip.defaults = {
    animation: true,
    placement: 'top',
    selector: false,
    template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
    trigger: 'hover focus',
    title: '',
    delay: 0,
    html: false,
    container: false
  }
  /* TOOLTIP NO CONFLICT
   * =================== */
  $.fn.tooltip.noConflict = function() {
    $.fn.tooltip = old
    return this
  }
}(window.jQuery);

! function($) {
  "use strict"; // jshint ;_;
  /* POPOVER PUBLIC CLASS DEFINITION
   * =============================== */
  var Popover = function(element, options) {
    this.init('popover', element, options)
  }
  /* NOTE: POPOVER EXTENDS BOOTSTRAP-TOOLTIP.js
     ========================================== */
  Popover.prototype = $.extend({}, $.fn.tooltip.Constructor.prototype, {
    constructor: Popover,
    setContent: function() {
      var $tip = this.tip(),
        title = this.getTitle(),
        content = this.getContent()
      $tip.find('.popover-title')[this.options.html ? 'html' : 'text'](title)
      $tip.find('.popover-content')[this.options.html ? 'html' : 'text'](content)
      $tip.removeClass('fade top bottom left right in')
    },
    hasContent: function() {
      return this.getTitle() || this.getContent()
    },
    getContent: function() {
      var content, $e = this.$element,
        o = this.options
      content = (typeof o.content == 'function' ? o.content.call($e[0]) : o.content) || $e.attr('data-content')
      return content
    },
    tip: function() {
      if (!this.$tip) {
        this.$tip = $(this.options.template)
      }
      return this.$tip
    },
    destroy: function() {
      this.hide().$element.off('.' + this.type).removeData(this.type)
    }
  })
  /* POPOVER PLUGIN DEFINITION
   * ======================= */
  var old = $.fn.popover
  $.fn.popover = function(option) {
    return this.each(function() {
      var $this = $(this),
        data = $this.data('popover'),
        options = typeof option == 'object' && option
      if (!data) $this.data('popover', (data = new Popover(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }
  $.fn.popover.Constructor = Popover
  $.fn.popover.defaults = $.extend({}, $.fn.tooltip.defaults, {
    placement: 'right',
    trigger: 'click',
    content: '',
    template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
  })
  /* POPOVER NO CONFLICT
   * =================== */
  $.fn.popover.noConflict = function() {
    $.fn.popover = old
    return this
  }
}(window.jQuery);

var ginge = (function() {
  var settings = {
    mapType: 'google',
    branchCount: 0,
    famLegend: null
  };
  var template = {};
  var parseNames = {};

  var onReady = function(options) {
    //console.log(options);
    loadTemplates();
    settings = jQuery.extend(settings, options || (options = {}));
  };

  var getCookie = function(sName) {
    sName = sName.toLowerCase();
    var oCrumbles = document.cookie.split(';');
    for (var i = 0; i < oCrumbles.length; i++) {
      var oPair = oCrumbles[i].split('=');
      var sKey = decodeURIComponent(oPair[0].trim().toLowerCase());
      var sValue = oPair.length > 1 ? oPair[1] : '';
      if (sKey == sName) return decodeURIComponent(sValue);
    }
    return '';
  }

  var setCookie = function(sName, sValue) {
    var oDate = new Date();
    oDate.setYear(oDate.getFullYear() + 1);
    var sCookie = encodeURIComponent(sName) + '=' + encodeURIComponent(sValue) + ';expires=' + oDate.toGMTString() + ';path=/';
    document.cookie = sCookie;
  }

  var clearCookie = function(sName) {
    setCookie(sName, '');
  }

  var loadIndi = function(id, hilight, treetop) {
    jQuery.ajax({
      url: gtObj.gt_rest_url+"genealogical-tree/v1/member/indi_" + id + ".js",
      cache: true,
      dataType: "json"
    }).done(function(jsonIndi) {
      var childof = jsonIndi.root.indi.childof;
      if (childof) {
        jQuery.ajax({
          url: gtObj.gt_rest_url+"genealogical-tree/v1/family/fam_" + childof + ".js",
          cache: true,
          dataType: "json"
        }).done(function(jsonFam) {
          treeTop(jsonIndi.root.indi, jsonFam.root.fam, hilight);
        }).fail(function(jqXHR, textStatus) {
          alert("id=" + id + " : " + textStatus);
        });
      } else {
        treeTop(jsonIndi.root.indi, null, hilight);
      }
    }).fail(function(jqXHR, textStatus) {
      alert("id=" + id + " : " + textStatus);
    });
  };

  var loadFam = function(famId, indiId, hilight) {
    //console.log('loadFam run')
    var url = gtObj.gt_rest_url+"genealogical-tree/v1/family/fam_" + famId + ".js";
    jQuery.ajax({
      url: url,
      cache: true,
      dataType: "json"
    }).done(function(json) {

      populateFam(json, indiId, hilight);
    }).fail(function(jqXHR, textStatus) {
      alert(url + " : " + textStatus);
    });
  };
  var addToFamLegend = function(id, first, last, year) {
    if (first == "...") f = "Ω" + first;
    else f = " " + first;
    if (last == "...") l = "Ω" + last;
    else l = " " + last;
    parseNames['names'].push({
      "id": id,
      "first": f,
      "last": l,
      "year": year
    });
  };
  var populateFam = function(json, indiId, hilight) {
    var fam = json.root.fam;
    var viewHelpers = {
      indiId: indiId,
      fb: getCookie('facebook'),
      tw: getCookie('twitter'),
      treeBranch: function(famId, indiId, hilight) {
        settings.branchCount++;
        loadFam(famId, indiId, hilight);
      }
    };
    fam.hilight = hilight;
    var data = {
      fam
    };
    _.extend(data, viewHelpers);
    jQuery("#fam_" + fam.id).html(template.treebranch(data));
    var partner;
    if (indiId == fam.wife.ref) partner = fam.husb;
    else partner = fam.wife;
    var birt = _.findWhere(partner.event, {
      'type': 'birt'
    });
    var birtTxt = "";
    if (birt && birt.date) birtTxt = birt.date.value;
    addToFamLegend(partner.ref, partner.name.first, partner.name.last, birtTxt);
    if (fam.children) {
      _.each(fam.children, function(child) {
        var birt = _.findWhere(child.event, {
          'type': 'birt'
        });
        var birtTxt = "";
        if (birt && birt.date) birtTxt = birt.date.value;
        addToFamLegend(child.ref, child.name.first, child.name.last, birtTxt);
      });
    }
    document.body.scrollLeft = (jQuery(document).width() / 2) - (jQuery("body").prop("clientWidth") / 2);
    settings.branchCount--;
    if (settings.branchCount === 0) {
      refreshLegend();
    }
  };
  var treeTop = function(indi, fam, hilight) {
    indi.hilight = hilight;
    parseNames = JSON.parse('{ "names": [] }');
    settings.branchCount = 1;
    var birt = _.findWhere(indi.event, {
      'type': 'birt'
    });
    var birtTxt = "";

    if (birt && birt.date) birtTxt = birt.date.value.substring(0, 4);
    jQuery('#fam-canvas h2').html(indi.name);
    addToFamLegend(indi.id, indi.name.first, indi.name.last, birtTxt);
    if (fam && fam.husb) {
      var birt = _.findWhere(fam.husb.event, {
        'type': 'birt'
      });
      var birtTxt = "";
      if (birt && birt.date) birtTxt = birt.date.value.substring(0, 4);
      addToFamLegend(fam.husb.ref, fam.husb.name.first, fam.husb.name.last, birtTxt);
    }
    if (fam && fam.wife) {
      var birt = _.findWhere(fam.wife.event, {
        'type': 'birt'
      });
      var birtTxt = "";
      if (birt && birt.date) birtTxt = birt.date.value.substring(0, 4);
      addToFamLegend(fam.wife.ref, fam.wife.name.first, fam.wife.name.last, birtTxt);
    }
    var viewHelpers = {
      fb: getCookie('facebook'),
      tw: getCookie('twitter'),
      treeBranch: function(famId, indiId, hilight) {

        loadFam(famId, indiId, hilight);
      },
      treeNoBranch: function() {
        refreshLegend();
      }
    };
    var data = {
      indi,
      fam
    };
    _.extend(data, viewHelpers);
    jQuery('#famTree').html(template.treetop(data));
  };
  var refreshLegend = function() {
    parseNames.names = _.chain(parseNames.names).sortBy(function(name) {
      return name.year;
    }).sortBy(function(name) {
      return name.last;
    }).sortBy(function(name) {
      return name.first;
    }).value();
    _.each(parseNames.names, function(name) {
      name.first = name.first.substr(1);
      name.last = name.last.substr(1);
    });
    jQuery('#cg-sidebar-inner').html(template.treenames(parseNames));
  };

  var loadTemplates = function() {
    var templateNames = ['plachead', 'placbody', 'event', 'parent', 'sibling', 'family', 'reference', 'treenames', 'treetop', 'treetoplegend', 'treebranch', 'treebranchlegend'];
    templateNames.forEach(function(templateName) {
      jQuery.get(gtObj.gt_dir_url+'public/js/templates/' + templateName + '.js', function(data) {
        template[templateName] = _.template(data);
      }, 'html');
    });
  };

  return {
    onReady: onReady,
    loadIndi: loadIndi
  };
})();


jQuery(document).ready(ginge.onReady());
(function($) {
  function closePopover() {
    $("#cg-popowner").popover('destroy');
    $("#cg-popowner").parent().removeClass("show-popover");
    $("#cg-popowner").removeAttr('id');
  }
  $(document).ready(function() {
    $(window).bind("ajaxSend", function() {
      $('#spinner').show();
    }).bind("ajaxStop", function() {
      $('#spinner').hide();
      
      var uls = $('#famTree ul');
      for (var i = 0; i < uls.length; i++) {
        var childrens = $(uls[i]).children();
        if (childrens.length > 1) {
          var heights = [];
          for (var j = 0; j < childrens.length; j++) {
            var height = $(childrens[j]).find('div.indi').first().height();
            heights.push(height)
          }
          for (var k = 0; k < childrens.length; k++) {
            $(childrens[k]).find('div.indi').first().height(Math.max.apply(Math, heights));
          }
        }
      }

    }).bind("ajaxError", function() {
      $('#spinner').hide();
    });
    
/* Premium Code Stripped by Freemius */

  });
})(jQuery);

!(function(f) {
  if (typeof exports === "object" && typeof module !== "undefined") {
    module.exports = f()
  } else if (typeof define === "function" && define.amd) {
    define([], f)
  } else {
    var g;
    if (typeof window !== "undefined") {
      g = window
    } else if (typeof global !== "undefined") {
      g = global
    } else if (typeof self !== "undefined") {
      g = self
    } else {
      g = this
    }
    g.panzoom = f()
  }
})(function() {
  var define, module, exports;
  return function() {
    function r(e, n, t) {
      function o(i, f) {
        if (!n[i]) {
          if (!e[i]) {
            var c = "function" == typeof require && require;
            if (!f && c) return c(i, !0);
            if (u) return u(i, !0);
            var a = new Error("Cannot find module '" + i + "'");
            throw a.code = "MODULE_NOT_FOUND", a
          }
          var p = n[i] = {
            exports: {}
          };
          e[i][0].call(p.exports, function(r) {
            var n = e[i][1][r];
            return o(n || r)
          }, p, p.exports, r, e, n, t)
        }
        return n[i].exports
      }
      for (var u = "function" == typeof require && require, i = 0; i < t.length; i++) o(t[i]);
      return o
    }
    return r
  }()({
    1: [function(require, module, exports) {
      "use strict";
      var wheel = require("wheel");
      var animate = require("amator");
      var eventify = require("ngraph.events");
      var kinetic = require("./lib/kinetic.js");
      var preventTextSelection = require("./lib/textSelectionInterceptor.js")();
      var Transform = require("./lib/transform.js");
      var makeSvgController = require("./lib/svgController.js");
      var makeDomController = require("./lib/domController.js");
      var defaultZoomSpeed = .065;
      var defaultDoubleTapZoomSpeed = 1.75;
      var doubleTapSpeedInMS = 300;
      module.exports = createPanZoom;

      function createPanZoom(domElement, options) {
        options = options || {};
        var panController = options.controller;
        if (!panController) {
          if (domElement instanceof SVGElement) {
            panController = makeSvgController(domElement, options)
          }
          if (domElement instanceof HTMLElement) {
            panController = makeDomController(domElement, options)
          }
        }
        if (!panController) {
          throw new Error("Cannot create panzoom for the current type of dom element")
        }
        var owner = panController.getOwner();
        var storedCTMResult = {
          x: 0,
          y: 0
        };
        var isDirty = false;
        var transform = new Transform;
        if (panController.initTransform) {
          panController.initTransform(transform)
        }
        var filterKey = typeof options.filterKey === "function" ? options.filterKey : noop;
        var pinchSpeed = typeof options.pinchSpeed === "number" ? options.pinchSpeed : 1;
        var bounds = options.bounds;
        var maxZoom = typeof options.maxZoom === "number" ? options.maxZoom : Number.POSITIVE_INFINITY;
        var minZoom = typeof options.minZoom === "number" ? options.minZoom : 0;
        var boundsPadding = typeof options.boundsPadding === "number" ? options.boundsPadding : .05;
        var zoomDoubleClickSpeed = typeof options.zoomDoubleClickSpeed === "number" ? options.zoomDoubleClickSpeed : defaultDoubleTapZoomSpeed;
        var beforeWheel = options.beforeWheel || noop;
        var speed = typeof options.zoomSpeed === "number" ? options.zoomSpeed : defaultZoomSpeed;
        validateBounds(bounds);
        if (options.autocenter) {
          autocenter()
        }
        var frameAnimation;
        var lastTouchEndTime = 0;
        var touchInProgress = false;
        var panstartFired = false;
        var mouseX;
        var mouseY;
        var pinchZoomLength;
        var smoothScroll;
        if ("smoothScroll" in options && !options.smoothScroll) {
          smoothScroll = rigidScroll()
        } else {
          smoothScroll = kinetic(getPoint, scroll, options.smoothScroll)
        }
        var moveByAnimation;
        var zoomToAnimation;
        var multiTouch;
        var paused = false;
        listenForEvents();
        var api = {
          dispose: dispose,
          moveBy: internalMoveBy,
          moveTo: moveTo,
          centerOn: centerOn,
          zoomTo: publicZoomTo,
          zoomAbs: zoomAbs,
          smoothZoom: smoothZoom,
          getTransform: getTransformModel,
          showRectangle: showRectangle,
          pause: pause,
          resume: resume,
          isPaused: isPaused
        };
        eventify(api);
        return api;

        function pause() {
          releaseEvents();
          paused = true
        }

        function resume() {
          if (paused) {
            listenForEvents();
            paused = false
          }
        }

        function isPaused() {
          return paused
        }

        function showRectangle(rect) {
          var clientRect = owner.getBoundingClientRect();
          var size = transformToScreen(clientRect.width, clientRect.height);
          var rectWidth = rect.right - rect.left;
          var rectHeight = rect.bottom - rect.top;
          if (!Number.isFinite(rectWidth) || !Number.isFinite(rectHeight)) {
            throw new Error("Invalid rectangle")
          }
          var dw = size.x / rectWidth;
          var dh = size.y / rectHeight;
          var scale = Math.min(dw, dh);
          transform.x = -(rect.left + rectWidth / 2) * scale + size.x / 2;
          transform.y = -(rect.top + rectHeight / 2) * scale + size.y / 2;
          transform.scale = scale
        }

        function transformToScreen(x, y) {
          if (panController.getScreenCTM) {
            var parentCTM = panController.getScreenCTM();
            var parentScaleX = parentCTM.a;
            var parentScaleY = parentCTM.d;
            var parentOffsetX = parentCTM.e;
            var parentOffsetY = parentCTM.f;
            storedCTMResult.x = x * parentScaleX - parentOffsetX;
            storedCTMResult.y = y * parentScaleY - parentOffsetY
          } else {
            storedCTMResult.x = x;
            storedCTMResult.y = y
          }
          return storedCTMResult
        }

        function autocenter() {
          var w;
          var h;
          var left = 0;
          var top = 0;
          var sceneBoundingBox = getBoundingBox();
          if (sceneBoundingBox) {
            left = sceneBoundingBox.left;
            top = sceneBoundingBox.top;
            w = sceneBoundingBox.right - sceneBoundingBox.left;
            h = sceneBoundingBox.bottom - sceneBoundingBox.top
          } else {
            var ownerRect = owner.getBoundingClientRect();
            w = ownerRect.width;
            h = ownerRect.height
          }
          var bbox = panController.getBBox();
          if (bbox.width === 0 || bbox.height === 0) {
            return
          }
          var dh = h / bbox.height;
          var dw = w / bbox.width;
          var scale = Math.min(dw, dh);
          transform.x = -(bbox.left + bbox.width / 2) * scale + w / 2 + left;
          transform.y = -(bbox.top + bbox.height / 2) * scale + h / 2 + top;
          transform.scale = scale
        }

        function getTransformModel() {
          return transform
        }

        function getPoint() {
          return {
            x: transform.x,
            y: transform.y
          }
        }

        function moveTo(x, y) {
          transform.x = x;
          transform.y = y;
          keepTransformInsideBounds();
          triggerEvent("pan");
          makeDirty()
        }

        function moveBy(dx, dy) {
          moveTo(transform.x + dx, transform.y + dy)
        }

        function keepTransformInsideBounds() {
          var boundingBox = getBoundingBox();
          if (!boundingBox) return;
          var adjusted = false;
          var clientRect = getClientRect();
          var diff = boundingBox.left - clientRect.right;
          if (diff > 0) {
            transform.x += diff;
            adjusted = true
          }
          diff = boundingBox.right - clientRect.left;
          if (diff < 0) {
            transform.x += diff;
            adjusted = true
          }
          diff = boundingBox.top - clientRect.bottom;
          if (diff > 0) {
            transform.y += diff;
            adjusted = true
          }
          diff = boundingBox.bottom - clientRect.top;
          if (diff < 0) {
            transform.y += diff;
            adjusted = true
          }
          return adjusted
        }

        function getBoundingBox() {
          if (!bounds) return;
          if (typeof bounds === "boolean") {
            var ownerRect = owner.getBoundingClientRect();
            var sceneWidth = ownerRect.width;
            var sceneHeight = ownerRect.height;
            return {
              left: sceneWidth * boundsPadding,
              top: sceneHeight * boundsPadding,
              right: sceneWidth * (1 - boundsPadding),
              bottom: sceneHeight * (1 - boundsPadding)
            }
          }
          return bounds
        }

        function getClientRect() {
          var bbox = panController.getBBox();
          var leftTop = client(bbox.left, bbox.top);
          return {
            left: leftTop.x,
            top: leftTop.y,
            right: bbox.width * transform.scale + leftTop.x,
            bottom: bbox.height * transform.scale + leftTop.y
          }
        }

        function client(x, y) {
          return {
            x: x * transform.scale + transform.x,
            y: y * transform.scale + transform.y
          }
        }

        function makeDirty() {
          isDirty = true;
          frameAnimation = window.requestAnimationFrame(frame)
        }

        function zoomByRatio(clientX, clientY, ratio) {
          if (isNaN(clientX) || isNaN(clientY) || isNaN(ratio)) {
            throw new Error("zoom requires valid numbers")
          }
          var newScale = transform.scale * ratio;
          if (newScale < minZoom) {
            if (transform.scale === minZoom) return;
            ratio = minZoom / transform.scale
          }
          if (newScale > maxZoom) {
            if (transform.scale === maxZoom) return;
            ratio = maxZoom / transform.scale
          }
          var size = transformToScreen(clientX, clientY);
          transform.x = size.x - ratio * (size.x - transform.x);
          transform.y = size.y - ratio * (size.y - transform.y);
          var transformAdjusted = keepTransformInsideBounds();
          if (!transformAdjusted) transform.scale *= ratio;
          triggerEvent("zoom");
          makeDirty()
        }

        function zoomAbs(clientX, clientY, zoomLevel) {
          var ratio = zoomLevel / transform.scale;
          zoomByRatio(clientX, clientY, ratio)
        }

        function centerOn(ui) {
          var parent = ui.ownerSVGElement;
          if (!parent) throw new Error("ui element is required to be within the scene");
          var clientRect = ui.getBoundingClientRect();
          var cx = clientRect.left + clientRect.width / 2;
          var cy = clientRect.top + clientRect.height / 2;
          var container = parent.getBoundingClientRect();
          var dx = container.width / 2 - cx;
          var dy = container.height / 2 - cy;
          internalMoveBy(dx, dy, true)
        }

        function internalMoveBy(dx, dy, smooth) {
          if (!smooth) {
            return moveBy(dx, dy)
          }
          if (moveByAnimation) moveByAnimation.cancel();
          var from = {
            x: 0,
            y: 0
          };
          var to = {
            x: dx,
            y: dy
          };
          var lastX = 0;
          var lastY = 0;
          moveByAnimation = animate(from, to, {
            step: function(v) {
              moveBy(v.x - lastX, v.y - lastY);
              lastX = v.x;
              lastY = v.y
            }
          })
        }

        function scroll(x, y) {
          cancelZoomAnimation();
          moveTo(x, y)
        }

        function dispose() {
          releaseEvents()
        }

        function listenForEvents() {
          owner.addEventListener("mousedown", onMouseDown);
          owner.addEventListener("dblclick", onDoubleClick);
          owner.addEventListener("touchstart", onTouch);
          owner.addEventListener("keydown", onKeyDown);
          wheel.addWheelListener(owner, onMouseWheel);
          makeDirty()
        }

        function releaseEvents() {
          wheel.removeWheelListener(owner, onMouseWheel);
          owner.removeEventListener("mousedown", onMouseDown);
          owner.removeEventListener("keydown", onKeyDown);
          owner.removeEventListener("dblclick", onDoubleClick);
          owner.removeEventListener("touchstart", onTouch);
          if (frameAnimation) {
            window.cancelAnimationFrame(frameAnimation);
            frameAnimation = 0
          }
          smoothScroll.cancel();
          releaseDocumentMouse();
          releaseTouches();
          triggerPanEnd()
        }

        function frame() {
          if (isDirty) applyTransform()
        }

        function applyTransform() {
          isDirty = false;
          panController.applyTransform(transform);
          triggerEvent("transform");
          frameAnimation = 0
        }

        function onKeyDown(e) {
          var x = 0,
            y = 0,
            z = 0;
          if (e.keyCode === 38) {
            y = 1
          } else if (e.keyCode === 40) {
            y = -1
          } else if (e.keyCode === 37) {
            x = 1
          } else if (e.keyCode === 39) {
            x = -1
          } else if (e.keyCode === 189 || e.keyCode === 109) {
            z = 1
          } else if (e.keyCode === 187 || e.keyCode === 107) {
            z = -1
          }
          if (filterKey(e, x, y, z)) {
            return
          }
          if (x || y) {
            e.preventDefault();
            e.stopPropagation();
            var clientRect = owner.getBoundingClientRect();
            var offset = Math.min(clientRect.width, clientRect.height);
            var moveSpeedRatio = .05;
            var dx = offset * moveSpeedRatio * x;
            var dy = offset * moveSpeedRatio * y;
            internalMoveBy(dx, dy)
          }
          if (z) {
            var scaleMultiplier = getScaleMultiplier(z);
            var ownerRect = owner.getBoundingClientRect();
            publicZoomTo(ownerRect.width / 2, ownerRect.height / 2, scaleMultiplier)
          }
        }

        function onTouch(e) {
          beforeTouch(e);
          if (e.touches.length === 1) {
            return handleSingleFingerTouch(e, e.touches[0])
          } else if (e.touches.length === 2) {
            pinchZoomLength = getPinchZoomLength(e.touches[0], e.touches[1]);
            multiTouch = true;
            startTouchListenerIfNeeded()
          }
        }

        function beforeTouch(e) {
          if (options.onTouch && !options.onTouch(e)) {
            return
          }
          e.stopPropagation();
          e.preventDefault()
        }

        function beforeDoubleClick(e) {
          if (options.onDoubleClick && !options.onDoubleClick(e)) {
            return
          }
          e.preventDefault();
          e.stopPropagation()
        }

        function handleSingleFingerTouch(e) {
          var touch = e.touches[0];
          var offset = getOffsetXY(touch);
          mouseX = offset.x;
          mouseY = offset.y;
          smoothScroll.cancel();
          startTouchListenerIfNeeded()
        }

        function startTouchListenerIfNeeded() {
          if (!touchInProgress) {
            touchInProgress = true;
            document.addEventListener("touchmove", handleTouchMove);
            document.addEventListener("touchend", handleTouchEnd);
            document.addEventListener("touchcancel", handleTouchEnd)
          }
        }

        function handleTouchMove(e) {
          if (e.touches.length === 1) {
            e.stopPropagation();
            var touch = e.touches[0];
            var offset = getOffsetXY(touch);
            var dx = offset.x - mouseX;
            var dy = offset.y - mouseY;
            if (dx !== 0 && dy !== 0) {
              triggerPanStart()
            }
            mouseX = offset.x;
            mouseY = offset.y;
            var point = transformToScreen(dx, dy);
            internalMoveBy(point.x, point.y)
          } else if (e.touches.length === 2) {
            multiTouch = true;
            var t1 = e.touches[0];
            var t2 = e.touches[1];
            var currentPinchLength = getPinchZoomLength(t1, t2);
            var scaleMultiplier = 1 + (currentPinchLength / pinchZoomLength - 1) * pinchSpeed;
            mouseX = (t1.clientX + t2.clientX) / 2;
            mouseY = (t1.clientY + t2.clientY) / 2;
            publicZoomTo(mouseX, mouseY, scaleMultiplier);
            pinchZoomLength = currentPinchLength;
            e.stopPropagation();
            e.preventDefault()
          }
        }

        function handleTouchEnd(e) {
          if (e.touches.length > 0) {
            var offset = getOffsetXY(e.touches[0]);
            mouseX = offset.x;
            mouseY = offset.y
          } else {
            var now = new Date;
            if (now - lastTouchEndTime < doubleTapSpeedInMS) {
              smoothZoom(mouseX, mouseY, zoomDoubleClickSpeed)
            }
            lastTouchEndTime = now;
            touchInProgress = false;
            triggerPanEnd();
            releaseTouches()
          }
        }

        function getPinchZoomLength(finger1, finger2) {
          var dx = finger1.clientX - finger2.clientX;
          var dy = finger1.clientY - finger2.clientY;
          return Math.sqrt(dx * dx + dy * dy)
        }

        function onDoubleClick(e) {
          beforeDoubleClick(e);
          var offset = getOffsetXY(e);
          smoothZoom(offset.x, offset.y, zoomDoubleClickSpeed)
        }

        function onMouseDown(e) {
          if (touchInProgress) {
            e.stopPropagation();
            return false
          }
          var isLeftButton = e.button === 1 && window.event !== null || e.button === 0;
          if (!isLeftButton) return;
          smoothScroll.cancel();
          var offset = getOffsetXY(e);
          var point = transformToScreen(offset.x, offset.y);
          mouseX = point.x;
          mouseY = point.y;
          document.addEventListener("mousemove", onMouseMove);
          document.addEventListener("mouseup", onMouseUp);
          preventTextSelection.capture(e.target || e.srcElement);
          return false
        }

        function onMouseMove(e) {
          if (touchInProgress) return;
          triggerPanStart();
          var offset = getOffsetXY(e);
          var point = transformToScreen(offset.x, offset.y);
          var dx = point.x - mouseX;
          var dy = point.y - mouseY;
          mouseX = point.x;
          mouseY = point.y;
          internalMoveBy(dx, dy)
        }

        function onMouseUp() {
          preventTextSelection.release();
          triggerPanEnd();
          releaseDocumentMouse()
        }

        function releaseDocumentMouse() {
          document.removeEventListener("mousemove", onMouseMove);
          document.removeEventListener("mouseup", onMouseUp);
          panstartFired = false
        }

        function releaseTouches() {
          document.removeEventListener("touchmove", handleTouchMove);
          document.removeEventListener("touchend", handleTouchEnd);
          document.removeEventListener("touchcancel", handleTouchEnd);
          panstartFired = false;
          multiTouch = false
        }

        function onMouseWheel(e) {
          if (beforeWheel(e)) return;
          smoothScroll.cancel();
          var scaleMultiplier = getScaleMultiplier(e.deltaY);
          if (scaleMultiplier !== 1) {
            var offset = getOffsetXY(e);
            publicZoomTo(offset.x, offset.y, scaleMultiplier);
            e.preventDefault()
          }
        }

        function getOffsetXY(e) {
          var offsetX, offsetY;
          var ownerRect = owner.getBoundingClientRect();
          offsetX = e.clientX - ownerRect.left;
          offsetY = e.clientY - ownerRect.top;
          return {
            x: offsetX,
            y: offsetY
          }
        }

        function smoothZoom(clientX, clientY, scaleMultiplier) {
          var fromValue = transform.scale;
          var from = {
            scale: fromValue
          };
          var to = {
            scale: scaleMultiplier * fromValue
          };
          smoothScroll.cancel();
          cancelZoomAnimation();
          zoomToAnimation = animate(from, to, {
            step: function(v) {
              zoomAbs(clientX, clientY, v.scale)
            }
          })
        }

        function publicZoomTo(clientX, clientY, scaleMultiplier) {
          smoothScroll.cancel();
          cancelZoomAnimation();
          return zoomByRatio(clientX, clientY, scaleMultiplier)
        }

        function cancelZoomAnimation() {
          if (zoomToAnimation) {
            zoomToAnimation.cancel();
            zoomToAnimation = null
          }
        }

        function getScaleMultiplier(delta) {
          var scaleMultiplier = 1;
          if (delta > 0) {
            scaleMultiplier = 1 - speed
          } else if (delta < 0) {
            scaleMultiplier = 1 + speed
          }
          return scaleMultiplier
        }

        function triggerPanStart() {
          if (!panstartFired) {
            triggerEvent("panstart");
            panstartFired = true;
            smoothScroll.start()
          }
        }

        function triggerPanEnd() {
          if (panstartFired) {
            if (!multiTouch) smoothScroll.stop();
            triggerEvent("panend")
          }
        }

        function triggerEvent(name) {
          api.fire(name, api)
        }
      }

      function noop() {}

      function validateBounds(bounds) {
        var boundsType = typeof bounds;
        if (boundsType === "undefined" || boundsType === "boolean") return;
        var validBounds = isNumber(bounds.left) && isNumber(bounds.top) && isNumber(bounds.bottom) && isNumber(bounds.right);
        if (!validBounds) throw new Error("Bounds object is not valid. It can be: " + "undefined, boolean (true|false) or an object {left, top, right, bottom}")
      }

      function isNumber(x) {
        return Number.isFinite(x)
      }

      function isNaN(value) {
        if (Number.isNaN) {
          return Number.isNaN(value)
        }
        return value !== value
      }

      function rigidScroll() {
        return {
          start: noop,
          stop: noop,
          cancel: noop
        }
      }

      function autoRun() {
        if (typeof document === "undefined") return;
        var scripts = document.getElementsByTagName("script");
        if (!scripts) return;
        var panzoomScript;
        Array.from(scripts).forEach(function(x) {
          if (x.src && x.src.match(/\bpanzoom(\.min)?\.js/)) {
            panzoomScript = x
          }
        });
        if (!panzoomScript) return;
        var query = panzoomScript.getAttribute("query");
        if (!query) return;
        var globalName = panzoomScript.getAttribute("name") || "pz";
        var started = Date.now();
        tryAttach();

        function tryAttach() {
          var el = document.querySelector(query);
          if (!el) {
            var now = Date.now();
            var elapsed = now - started;
            if (elapsed < 2e3) {
              setTimeout(tryAttach, 100);
              return
            }
            console.error("Cannot find the panzoom element", globalName);
            return
          }
          var options = collectOptions(panzoomScript);
          //console.log(options);
          window[globalName] = createPanZoom(el, options)
        }

        function collectOptions(script) {
          var attrs = script.attributes;
          var options = {};
          for (var i = 0; i < attrs.length; ++i) {
            var attr = attrs[i];
            var nameValue = getPanzoomAttributeNameValue(attr);
            if (nameValue) {
              options[nameValue.name] = nameValue.value
            }
          }
          return options
        }

        function getPanzoomAttributeNameValue(attr) {
          if (!attr.name) return;
          var isPanZoomAttribute = attr.name[0] === "p" && attr.name[1] === "z" && attr.name[2] === "-";
          if (!isPanZoomAttribute) return;
          var name = attr.name.substr(3);
          var value = JSON.parse(attr.value);
          return {
            name: name,
            value: value
          }
        }
      }
      autoRun()
    }, {
      "./lib/domController.js": 2,
      "./lib/kinetic.js": 3,
      "./lib/svgController.js": 4,
      "./lib/textSelectionInterceptor.js": 5,
      "./lib/transform.js": 6,
      amator: 7,
      "ngraph.events": 9,
      wheel: 10
    }],
    2: [function(require, module, exports) {
      module.exports = makeDomController;

      function makeDomController(domElement, options) {
        var elementValid = domElement instanceof HTMLElement;
        if (!elementValid) {
          throw new Error("svg element is required for svg.panzoom to work")
        }
        var owner = domElement.parentElement;
        if (!owner) {
          throw new Error("Do not apply panzoom to the detached DOM element. ")
        }
        domElement.scrollTop = 0;
        if (!options.disableKeyboardInteraction) {
          owner.setAttribute("tabindex", 0)
        }
        var api = {
          getBBox: getBBox,
          getOwner: getOwner,
          applyTransform: applyTransform
        };
        return api;

        function getOwner() {
          return owner
        }

        function getBBox() {
          return {
            left: 0,
            top: 0,
            width: domElement.clientWidth,
            height: domElement.clientHeight
          }
        }

        function applyTransform(transform) {
          domElement.style.transformOrigin = "0 0 0";
          domElement.style.transform = "matrix(" + transform.scale + ", 0, 0, " + transform.scale + ", " + transform.x + ", " + transform.y + ")"
        }
      }
    }, {}],
    3: [function(require, module, exports) {
      module.exports = kinetic;

      function kinetic(getPoint, scroll, settings) {
        if (typeof settings !== "object") {
          settings = {}
        }
        var minVelocity = typeof settings.minVelocity === "number" ? settings.minVelocity : 5;
        var amplitude = typeof settings.amplitude === "number" ? settings.amplitude : .25;
        var lastPoint;
        var timestamp;
        var timeConstant = 342;
        var ticker;
        var vx, targetX, ax;
        var vy, targetY, ay;
        var raf;
        return {
          start: start,
          stop: stop,
          cancel: dispose
        };

        function dispose() {
          window.clearInterval(ticker);
          window.cancelAnimationFrame(raf)
        }

        function start() {
          lastPoint = getPoint();
          ax = ay = vx = vy = 0;
          timestamp = new Date;
          window.clearInterval(ticker);
          window.cancelAnimationFrame(raf);
          ticker = window.setInterval(track, 100)
        }

        function track() {
          var now = Date.now();
          var elapsed = now - timestamp;
          timestamp = now;
          var currentPoint = getPoint();
          var dx = currentPoint.x - lastPoint.x;
          var dy = currentPoint.y - lastPoint.y;
          lastPoint = currentPoint;
          var dt = 1e3 / (1 + elapsed);
          vx = .8 * dx * dt + .2 * vx;
          vy = .8 * dy * dt + .2 * vy
        }

        function stop() {
          window.clearInterval(ticker);
          window.cancelAnimationFrame(raf);
          var currentPoint = getPoint();
          targetX = currentPoint.x;
          targetY = currentPoint.y;
          timestamp = Date.now();
          if (vx < -minVelocity || vx > minVelocity) {
            ax = amplitude * vx;
            targetX += ax
          }
          if (vy < -minVelocity || vy > minVelocity) {
            ay = amplitude * vy;
            targetY += ay
          }
          raf = window.requestAnimationFrame(autoScroll)
        }

        function autoScroll() {
          var elapsed = Date.now() - timestamp;
          var moving = false;
          var dx = 0;
          var dy = 0;
          if (ax) {
            dx = -ax * Math.exp(-elapsed / timeConstant);
            if (dx > .5 || dx < -.5) moving = true;
            else dx = ax = 0
          }
          if (ay) {
            dy = -ay * Math.exp(-elapsed / timeConstant);
            if (dy > .5 || dy < -.5) moving = true;
            else dy = ay = 0
          }
          if (moving) {
            scroll(targetX + dx, targetY + dy);
            raf = window.requestAnimationFrame(autoScroll)
          }
        }
      }
    }, {}],
    4: [function(require, module, exports) {
      module.exports = makeSvgController;

      function makeSvgController(svgElement, options) {
        var elementValid = svgElement instanceof SVGElement;
        if (!elementValid) {
          throw new Error("svg element is required for svg.panzoom to work")
        }
        var owner = svgElement.ownerSVGElement;
        if (!owner) {
          throw new Error("Do not apply panzoom to the root <svg> element. " + "Use its child instead (e.g. <g></g>). " + "As of March 2016 only FireFox supported transform on the root element")
        }
        if (!options.disableKeyboardInteraction) {
          owner.setAttribute("tabindex", 0)
        }
        var api = {
          getBBox: getBBox,
          getScreenCTM: getScreenCTM,
          getOwner: getOwner,
          applyTransform: applyTransform,
          initTransform: initTransform
        };
        return api;

        function getOwner() {
          return owner
        }

        function getBBox() {
          var bbox = svgElement.getBBox();
          return {
            left: bbox.x,
            top: bbox.y,
            width: bbox.width,
            height: bbox.height
          }
        }

        function getScreenCTM() {
          return owner.getScreenCTM()
        }

        function initTransform(transform) {
          var screenCTM = svgElement.getScreenCTM();
          transform.x = screenCTM.e;
          transform.y = screenCTM.f;
          transform.scale = screenCTM.a;
          owner.removeAttributeNS(null, "viewBox")
        }

        function applyTransform(transform) {
          svgElement.setAttribute("transform", "matrix(" + transform.scale + " 0 0 " + transform.scale + " " + transform.x + " " + transform.y + ")")
        }
      }
    }, {}],
    5: [function(require, module, exports) {
      module.exports = createTextSelectionInterceptor;

      function createTextSelectionInterceptor() {
        var dragObject;
        var prevSelectStart;
        var prevDragStart;
        return {
          capture: capture,
          release: release
        };

        function capture(domObject) {
          prevSelectStart = window.document.onselectstart;
          prevDragStart = window.document.ondragstart;
          window.document.onselectstart = disabled;
          dragObject = domObject;
          dragObject.ondragstart = disabled
        }

        function release() {
          window.document.onselectstart = prevSelectStart;
          if (dragObject) dragObject.ondragstart = prevDragStart
        }
      }

      function disabled(e) {
        e.stopPropagation();
        return false
      }
    }, {}],
    6: [function(require, module, exports) {
      module.exports = Transform;

      function Transform() {
        this.x = 0;
        this.y = 0;
        this.scale = 1
      }
    }, {}],
    7: [function(require, module, exports) {
      var BezierEasing = require("bezier-easing");
      var animations = {
        ease: BezierEasing(.25, .1, .25, 1),
        easeIn: BezierEasing(.42, 0, 1, 1),
        easeOut: BezierEasing(0, 0, .58, 1),
        easeInOut: BezierEasing(.42, 0, .58, 1),
        linear: BezierEasing(0, 0, 1, 1)
      };
      module.exports = animate;
      module.exports.makeAggregateRaf = makeAggregateRaf;
      module.exports.sharedScheduler = makeAggregateRaf();

      function animate(source, target, options) {
        var start = Object.create(null);
        var diff = Object.create(null);
        options = options || {};
        var easing = typeof options.easing === "function" ? options.easing : animations[options.easing];
        if (!easing) {
          if (options.easing) {
            console.warn("Unknown easing function in amator: " + options.easing)
          }
          easing = animations.ease
        }
        var step = typeof options.step === "function" ? options.step : noop;
        var done = typeof options.done === "function" ? options.done : noop;
        var scheduler = getScheduler(options.scheduler);
        var keys = Object.keys(target);
        keys.forEach(function(key) {
          start[key] = source[key];
          diff[key] = target[key] - source[key]
        });
        var durationInMs = typeof options.duration === "number" ? options.duration : 400;
        var durationInFrames = Math.max(1, durationInMs * .06);
        var previousAnimationId;
        var frame = 0;
        previousAnimationId = scheduler.next(loop);
        return {
          cancel: cancel
        };

        function cancel() {
          scheduler.cancel(previousAnimationId);
          previousAnimationId = 0
        }

        function loop() {
          var t = easing(frame / durationInFrames);
          frame += 1;
          setValues(t);
          if (frame <= durationInFrames) {
            previousAnimationId = scheduler.next(loop);
            step(source)
          } else {
            previousAnimationId = 0;
            setTimeout(function() {
              done(source)
            }, 0)
          }
        }

        function setValues(t) {
          keys.forEach(function(key) {
            source[key] = diff[key] * t + start[key]
          })
        }
      }

      function noop() {}

      function getScheduler(scheduler) {
        if (!scheduler) {
          var canRaf = typeof window !== "undefined" && window.requestAnimationFrame;
          return canRaf ? rafScheduler() : timeoutScheduler()
        }
        if (typeof scheduler.next !== "function") throw new Error("Scheduler is supposed to have next(cb) function");
        if (typeof scheduler.cancel !== "function") throw new Error("Scheduler is supposed to have cancel(handle) function");
        return scheduler
      }

      function rafScheduler() {
        return {
          next: window.requestAnimationFrame.bind(window),
          cancel: window.cancelAnimationFrame.bind(window)
        }
      }

      function timeoutScheduler() {
        return {
          next: function(cb) {
            return setTimeout(cb, 1e3 / 60)
          },
          cancel: function(id) {
            return clearTimeout(id)
          }
        }
      }

      function makeAggregateRaf() {
        var frontBuffer = new Set;
        var backBuffer = new Set;
        var frameToken = 0;
        return {
          next: next,
          cancel: next,
          clearAll: clearAll
        };

        function clearAll() {
          frontBuffer.clear();
          backBuffer.clear();
          cancelAnimationFrame(frameToken);
          frameToken = 0
        }

        function next(callback) {
          backBuffer.add(callback);
          renderNextFrame()
        }

        function renderNextFrame() {
          if (!frameToken) frameToken = requestAnimationFrame(renderFrame)
        }

        function renderFrame() {
          frameToken = 0;
          var t = backBuffer;
          backBuffer = frontBuffer;
          frontBuffer = t;
          frontBuffer.forEach(function(callback) {
            callback()
          });
          frontBuffer.clear()
        }

        function cancel(callback) {
          backBuffer.delete(callback)
        }
      }
    }, {
      "bezier-easing": 8
    }],
    8: [function(require, module, exports) {
      var NEWTON_ITERATIONS = 4;
      var NEWTON_MIN_SLOPE = .001;
      var SUBDIVISION_PRECISION = 1e-7;
      var SUBDIVISION_MAX_ITERATIONS = 10;
      var kSplineTableSize = 11;
      var kSampleStepSize = 1 / (kSplineTableSize - 1);
      var float32ArraySupported = typeof Float32Array === "function";

      function A(aA1, aA2) {
        return 1 - 3 * aA2 + 3 * aA1
      }

      function B(aA1, aA2) {
        return 3 * aA2 - 6 * aA1
      }

      function C(aA1) {
        return 3 * aA1
      }

      function calcBezier(aT, aA1, aA2) {
        return ((A(aA1, aA2) * aT + B(aA1, aA2)) * aT + C(aA1)) * aT
      }

      function getSlope(aT, aA1, aA2) {
        return 3 * A(aA1, aA2) * aT * aT + 2 * B(aA1, aA2) * aT + C(aA1)
      }

      function binarySubdivide(aX, aA, aB, mX1, mX2) {
        var currentX, currentT, i = 0;
        do {
          currentT = aA + (aB - aA) / 2;
          currentX = calcBezier(currentT, mX1, mX2) - aX;
          if (currentX > 0) {
            aB = currentT
          } else {
            aA = currentT
          }
        } while (Math.abs(currentX) > SUBDIVISION_PRECISION && ++i < SUBDIVISION_MAX_ITERATIONS);
        return currentT
      }

      function newtonRaphsonIterate(aX, aGuessT, mX1, mX2) {
        for (var i = 0; i < NEWTON_ITERATIONS; ++i) {
          var currentSlope = getSlope(aGuessT, mX1, mX2);
          if (currentSlope === 0) {
            return aGuessT
          }
          var currentX = calcBezier(aGuessT, mX1, mX2) - aX;
          aGuessT -= currentX / currentSlope
        }
        return aGuessT
      }

      function LinearEasing(x) {
        return x
      }
      module.exports = function bezier(mX1, mY1, mX2, mY2) {
        if (!(0 <= mX1 && mX1 <= 1 && 0 <= mX2 && mX2 <= 1)) {
          throw new Error("bezier x values must be in [0, 1] range")
        }
        if (mX1 === mY1 && mX2 === mY2) {
          return LinearEasing
        }
        var sampleValues = float32ArraySupported ? new Float32Array(kSplineTableSize) : new Array(kSplineTableSize);
        for (var i = 0; i < kSplineTableSize; ++i) {
          sampleValues[i] = calcBezier(i * kSampleStepSize, mX1, mX2)
        }

        function getTForX(aX) {
          var intervalStart = 0;
          var currentSample = 1;
          var lastSample = kSplineTableSize - 1;
          for (; currentSample !== lastSample && sampleValues[currentSample] <= aX; ++currentSample) {
            intervalStart += kSampleStepSize
          }--currentSample;
          var dist = (aX - sampleValues[currentSample]) / (sampleValues[currentSample + 1] - sampleValues[currentSample]);
          var guessForT = intervalStart + dist * kSampleStepSize;
          var initialSlope = getSlope(guessForT, mX1, mX2);
          if (initialSlope >= NEWTON_MIN_SLOPE) {
            return newtonRaphsonIterate(aX, guessForT, mX1, mX2)
          } else if (initialSlope === 0) {
            return guessForT
          } else {
            return binarySubdivide(aX, intervalStart, intervalStart + kSampleStepSize, mX1, mX2)
          }
        }
        return function BezierEasing(x) {
          if (x === 0) {
            return 0
          }
          if (x === 1) {
            return 1
          }
          return calcBezier(getTForX(x), mY1, mY2)
        }
      }
    }, {}],
    9: [function(require, module, exports) {
      module.exports = function(subject) {
        validateSubject(subject);
        var eventsStorage = createEventsStorage(subject);
        subject.on = eventsStorage.on;
        subject.off = eventsStorage.off;
        subject.fire = eventsStorage.fire;
        return subject
      };

      function createEventsStorage(subject) {
        var registeredEvents = Object.create(null);
        return {
          on: function(eventName, callback, ctx) {
            if (typeof callback !== "function") {
              throw new Error("callback is expected to be a function")
            }
            var handlers = registeredEvents[eventName];
            if (!handlers) {
              handlers = registeredEvents[eventName] = []
            }
            handlers.push({
              callback: callback,
              ctx: ctx
            });
            return subject
          },
          off: function(eventName, callback) {
            var wantToRemoveAll = typeof eventName === "undefined";
            if (wantToRemoveAll) {
              registeredEvents = Object.create(null);
              return subject
            }
            if (registeredEvents[eventName]) {
              var deleteAllCallbacksForEvent = typeof callback !== "function";
              if (deleteAllCallbacksForEvent) {
                delete registeredEvents[eventName]
              } else {
                var callbacks = registeredEvents[eventName];
                for (var i = 0; i < callbacks.length; ++i) {
                  if (callbacks[i].callback === callback) {
                    callbacks.splice(i, 1)
                  }
                }
              }
            }
            return subject
          },
          fire: function(eventName) {
            var callbacks = registeredEvents[eventName];
            if (!callbacks) {
              return subject
            }
            var fireArguments;
            if (arguments.length > 1) {
              fireArguments = Array.prototype.splice.call(arguments, 1)
            }
            for (var i = 0; i < callbacks.length; ++i) {
              var callbackInfo = callbacks[i];
              callbackInfo.callback.apply(callbackInfo.ctx, fireArguments)
            }
            return subject
          }
        }
      }

      function validateSubject(subject) {
        if (!subject) {
          throw new Error("Eventify cannot use falsy object as events subject")
        }
        var reservedWords = ["on", "fire", "off"];
        for (var i = 0; i < reservedWords.length; ++i) {
          if (subject.hasOwnProperty(reservedWords[i])) {
            throw new Error("Subject cannot be eventified, since it already has property '" + reservedWords[i] + "'")
          }
        }
      }
    }, {}],
    10: [function(require, module, exports) {
      module.exports = addWheelListener;
      module.exports.addWheelListener = addWheelListener;
      module.exports.removeWheelListener = removeWheelListener;
      var prefix = "",
        _addEventListener, _removeEventListener, support;
      detectEventModel(typeof window !== "undefined" && window, typeof document !== "undefined" && document);

      function addWheelListener(elem, callback, useCapture) {
        _addWheelListener(elem, support, callback, useCapture);
        if (support == "DOMMouseScroll") {
          _addWheelListener(elem, "MozMousePixelScroll", callback, useCapture)
        }
      }

      function removeWheelListener(elem, callback, useCapture) {
        _removeWheelListener(elem, support, callback, useCapture);
        if (support == "DOMMouseScroll") {
          _removeWheelListener(elem, "MozMousePixelScroll", callback, useCapture)
        }
      }

      function _addWheelListener(elem, eventName, callback, useCapture) {
        elem[_addEventListener](prefix + eventName, support == "wheel" ? callback : function(originalEvent) {
          !originalEvent && (originalEvent = window.event);
          var event = {
            originalEvent: originalEvent,
            target: originalEvent.target || originalEvent.srcElement,
            type: "wheel",
            deltaMode: originalEvent.type == "MozMousePixelScroll" ? 0 : 1,
            deltaX: 0,
            deltaY: 0,
            deltaZ: 0,
            clientX: originalEvent.clientX,
            clientY: originalEvent.clientY,
            preventDefault: function() {
              originalEvent.preventDefault ? originalEvent.preventDefault() : originalEvent.returnValue = false
            },
            stopPropagation: function() {
              if (originalEvent.stopPropagation) originalEvent.stopPropagation()
            },
            stopImmediatePropagation: function() {
              if (originalEvent.stopImmediatePropagation) originalEvent.stopImmediatePropagation()
            }
          };
          if (support == "mousewheel") {
            event.deltaY = -1 / 40 * originalEvent.wheelDelta;
            originalEvent.wheelDeltaX && (event.deltaX = -1 / 40 * originalEvent.wheelDeltaX)
          } else {
            event.deltaY = originalEvent.detail
          }
          return callback(event)
        }, useCapture || false)
      }

      function _removeWheelListener(elem, eventName, callback, useCapture) {
        elem[_removeEventListener](prefix + eventName, callback, useCapture || false)
      }

      function detectEventModel(window, document) {
        if (window && window.addEventListener) {
          _addEventListener = "addEventListener";
          _removeEventListener = "removeEventListener"
        } else {
          _addEventListener = "attachEvent";
          _removeEventListener = "detachEvent";
          prefix = "on"
        }
        if (document) {
          support = "onwheel" in document.createElement("div") ? "wheel" : document.onmousewheel !== undefined ? "mousewheel" : "DOMMouseScroll"
        } else {
          support = "wheel"
        }
      }
    }, {}]
  }, {}, [1])(1)
});
(function($) {
  'use strict';
  jQuery(window).on('load', function() {
    jQuery(document).ready(function() {
      var scene = document.getElementById('famTree');
      panzoom(scene);
    })
  });
})(jQuery);