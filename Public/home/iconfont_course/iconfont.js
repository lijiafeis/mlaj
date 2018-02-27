;(function(window) {

  var svgSprite = '<svg>' +
    '' +
    '<symbol id="icon-kecheng" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M728 64.6H138.5c-22.2 0-40.1 17.9-40.1 40v814.1c0 22.1 18 40 40.1 40H728c22.2 0 40.1-17.9 40.1-40V104.6c0-22.1-17.9-40-40.1-40zM391.5 470.8l-86.7-77.1c-7.6-6.7-19-6.7-26.6 0l-86.7 77.1v-260c0-11 9-20 20-20h160c11 0 20 9 20 20v260zM886.1 64.6h-14.8c-22.1 0-40 17.9-40 40v814.1c0 22.1 17.9 40 40 40h14.8c22.1 0 40-17.9 40-40V104.6c0-22.1-17.9-40-40-40z" fill="#1296DB" ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-kecheng1" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M953.86 89.689c-0.031-0.805-0.135-1.605-0.243-2.414-0.123-0.912-0.264-1.814-0.485-2.694-0.065-0.264-0.065-0.529-0.141-0.798-0.125-0.421-0.34-0.788-0.48-1.198-0.314-0.918-0.648-1.81-1.058-2.673a23.796 23.796 0 0 0-1.032-1.895 24.938 24.938 0 0 0-1.435-2.203 25.023 25.023 0 0 0-1.436-1.738 25.804 25.804 0 0 0-1.776-1.814 25.151 25.151 0 0 0-1.7-1.403 25.095 25.095 0 0 0-2.138-1.475 25.62 25.62 0 0 0-1.895-1.025 23.799 23.799 0 0 0-2.402-1.053 24.346 24.346 0 0 0-2.16-0.67 24.387 24.387 0 0 0-2.445-0.545c-0.832-0.139-1.668-0.204-2.52-0.253-0.503-0.032-0.977-0.151-1.48-0.151-0.318 0-0.615 0.082-0.928 0.092-0.814 0.032-1.624 0.134-2.44 0.248-0.902 0.124-1.781 0.26-2.65 0.476-0.27 0.07-0.552 0.07-0.827 0.146l-410.78 117.08L100.627 66.654c-0.275-0.082-0.55-0.082-0.825-0.152-0.869-0.216-1.75-0.351-2.65-0.476-0.816-0.108-1.625-0.216-2.436-0.242-0.318-0.011-0.609-0.098-0.928-0.098-0.508 0-0.982 0.124-1.484 0.151-0.847 0.055-1.69 0.12-2.52 0.253-0.832 0.135-1.636 0.33-2.446 0.546-0.728 0.2-1.452 0.41-2.158 0.674-0.826 0.303-1.62 0.659-2.403 1.048-0.646 0.318-1.273 0.654-1.889 1.025-0.75 0.454-1.452 0.95-2.143 1.475a22.497 22.497 0 0 0-3.475 3.222c-0.501 0.561-0.982 1.128-1.435 1.737-0.524 0.702-0.988 1.44-1.436 2.197-0.367 0.62-0.718 1.242-1.032 1.9-0.415 0.859-0.75 1.755-1.058 2.667-0.14 0.41-0.356 0.777-0.48 1.203-0.075 0.264-0.075 0.53-0.14 0.793-0.22 0.886-0.362 1.782-0.486 2.7-0.107 0.81-0.21 1.608-0.243 2.413-0.01 0.314-0.091 0.605-0.091 0.923V783.34h0.178c1.058 9.787 7.865 18.43 17.917 21.29l424.448 121.82 424.447-121.818c10.05-2.86 16.857-11.51 17.917-21.29h0.178V90.612c-0.001-0.313-0.082-0.611-0.093-0.923zM450.489 736.713c-8.17 22.446-33.219 34.126-55.664 25.957L174.5 682.48c-22.446-8.17-34.126-33.219-25.956-55.664 8.17-22.446 33.219-34.126 55.664-25.956l220.324 80.191c22.445 8.168 34.126 33.217 25.956 55.663z m0-173.8c-8.17 22.446-33.219 34.126-55.664 25.957L174.5 508.678c-22.446-8.169-34.126-33.218-25.956-55.663 8.17-22.447 33.219-34.127 55.664-25.957l220.324 80.192c22.445 8.168 34.126 33.217 25.956 55.663z m0-173.8c-8.17 22.445-33.219 34.125-55.664 25.956L174.5 334.878c-22.446-8.17-34.126-33.219-25.956-55.664 8.17-22.446 33.219-34.126 55.664-25.956l220.324 80.191c22.445 8.168 34.126 33.218 25.956 55.663zM841.15 674.508l-220.325 80.192c-22.446 8.17-47.495-3.511-55.664-25.957-8.17-22.446 3.51-47.494 25.956-55.663l220.324-80.192c22.446-8.17 47.495 3.511 55.664 25.956 8.17 22.446-3.51 47.494-25.955 55.664z m-94.41-59.027l0.027-0.167 0.243-0.415 0.103 0.308-0.374 0.274z m94.41-111.465L620.826 584.21c-22.446 8.17-47.495-3.511-55.664-25.957-8.17-22.446 3.51-47.494 25.956-55.663l220.324-80.192c22.446-8.169 47.495 3.511 55.664 25.956 8.17 22.446-3.51 47.494-25.956 55.664z m0-170.492l-220.325 80.192c-22.446 8.17-47.495-3.511-55.664-25.957-8.17-22.446 3.51-47.494 25.956-55.663l220.324-80.192c22.446-8.17 47.495 3.511 55.664 25.956 8.17 22.446-3.51 47.495-25.955 55.664z" fill="#25E0AE" ></path>' +
    '' +
    '</symbol>' +
    '' +
    '</svg>'
  var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
  var shouldInjectCss = script.getAttribute("data-injectcss")

  /**
   * document ready
   */
  var ready = function(fn) {
    if (document.addEventListener) {
      if (~["complete", "loaded", "interactive"].indexOf(document.readyState)) {
        setTimeout(fn, 0)
      } else {
        var loadFn = function() {
          document.removeEventListener("DOMContentLoaded", loadFn, false)
          fn()
        }
        document.addEventListener("DOMContentLoaded", loadFn, false)
      }
    } else if (document.attachEvent) {
      IEContentLoaded(window, fn)
    }

    function IEContentLoaded(w, fn) {
      var d = w.document,
        done = false,
        // only fire once
        init = function() {
          if (!done) {
            done = true
            fn()
          }
        }
        // polling for no errors
      var polling = function() {
        try {
          // throws errors until after ondocumentready
          d.documentElement.doScroll('left')
        } catch (e) {
          setTimeout(polling, 50)
          return
        }
        // no errors, fire

        init()
      };

      polling()
        // trying to always fire before onload
      d.onreadystatechange = function() {
        if (d.readyState == 'complete') {
          d.onreadystatechange = null
          init()
        }
      }
    }
  }

  /**
   * Insert el before target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var before = function(el, target) {
    target.parentNode.insertBefore(el, target)
  }

  /**
   * Prepend el to target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var prepend = function(el, target) {
    if (target.firstChild) {
      before(el, target.firstChild)
    } else {
      target.appendChild(el)
    }
  }

  function appendSvg() {
    var div, svg

    div = document.createElement('div')
    div.innerHTML = svgSprite
    svgSprite = null
    svg = div.getElementsByTagName('svg')[0]
    if (svg) {
      svg.setAttribute('aria-hidden', 'true')
      svg.style.position = 'absolute'
      svg.style.width = 0
      svg.style.height = 0
      svg.style.overflow = 'hidden'
      prepend(svg, document.body)
    }
  }

  if (shouldInjectCss && !window.__iconfont__svg__cssinject__) {
    window.__iconfont__svg__cssinject__ = true
    try {
      document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
    } catch (e) {
      console && console.log(e)
    }
  }

  ready(appendSvg)


})(window)