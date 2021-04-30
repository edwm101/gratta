export default function ({ store }, inject) {
  function moneyFormat(value, currency = 'TND') {
    if (!value) return '0 ' + currency
    value =
      value != 0 && !isNaN(value)
        ? value.toLocaleString('ar-TN', { minimumFractionDigits: 0 }) +
          ' ' +
          currency
        : '0 ' + currency
    return value.split('.').join(' ')
  }
  var method = {
    isImg: (url) => {
      return(url.match(/\.(jpeg|jpg|gif|png)$/) != null);
    },
    convertToSlug: (text) => {
      if (text) {
        return text
          .toLowerCase()
          .replace(/ /g, '-')
          .replace(/[^\w-]+/g, '')
      } else {
        return null
      }
    },
    isWebView: () => {
      var rules = [
        'WebView',
        '(iPhone|iPod|iPad)(?!.*Safari)',
        'Android.*(wv|.0.0.0)',
        'Linux; U; Android',
      ]
      var webviewRegExp = new RegExp('(' + rules.join('|') + ')', 'ig')

      return !!navigator.userAgent.match(webviewRegExp)
    },
    convSec: (secs, auto = false) => {
      secs = parseInt(secs)
      var days = Math.floor(secs / 86400)
      var hours = Math.floor(secs / 3600)
      var minutes = Math.floor((secs / 60) % 60)
      var seconds = secs % 60

      var d = days
      var h = hours < 24 ? hours : 0
      var m = minutes < 60 ? minutes : 0
      var s = seconds < 60 ? seconds : 0

      if (auto) {
        return (
          (d ? d + 'jours ' : '') +
          (h ? h + 'h ' : '') +
          (m ? m + 'm ' : '') +
          (s ? s + 's' : '')
        )
      } else {
      }
      return {
        d,
        h,
        m,
        s,
      }
    },
    groupBy: (items, key) => {
      const groups = items.reduce((groups, item) => {
        if (!groups[item[key]]) {
          groups[item[key]] = []
        }
        groups[item[key]].push(item)
        return groups
      }, {})

      // Edit: to add it in the array format instead
      return Object.keys(groups).map((key) => {
        return {
          key,
          items: groups[key],
        }
      })
    },
    rule_required: (value) => !!value || 'Obligatoire *',
    rule_min: (v) => (v && v.length >= 6) || 'Minimum 6 caractères',
    rule_lowerCase: (value) =>
      value.toUpperCase() != value || 'Une lettre minuscule',
    rule_upperCase: (value) =>
      value.toLowerCase() != value || 'Une lettre majuscule',
    rule_number: (value) => /\d/.test(value) || 'Un chiffre',
    rule_regexp: (value) =>
      !/^[a-zA-Z0-9]+$/.test(value) ||
      'Une symbole (_ ? ! € $ £ @ : + = - ou autres à définir)',
    rule_url: (value) =>
      /^[a-z0-9--]+$/.test(value) || "seuls l'alphabet et - sont acceptés",
    rule_email: (value) =>
      /^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_]+\.[a-zA-Z0-9\-_. ]+$/.test(value) ||
      'Email invalide',
    filterParams: (query) => {
      var params = {}
      if (query.min_price) params.min_price = query.min_price
      if (query.max_price) params.max_price = query.max_price
      if (query.min_odometer) params.min_odometer = query.min_odometer
      if (query.max_odometer) params.max_odometer = query.max_odometer
      if (query.min_year) params.min_year = query.min_year
      if (query.max_year) params.max_year = query.max_year
      if (query.cote) params.cote = query.cote
      if (query.cp) params.cp = query.cp
      if (query.date) params.date = query.date
      if (query.sort_type) params.sort_type = query.sort_type
      if (query.sortby) params.sortby = query.sortby
      if (query.md_body) params.md_body = query.md_body
      if (query.md_color) params.md_color = query.md_color
      if (query.md_gearbox) params.md_gearbox = query.md_gearbox
      if (query.md_fuel_type) params.md_fuel_type = query.md_fuel_type
      if (query.gov) params.gov = query.gov
      return params
    },
    loadScript: (src) => {
      return new Promise(function (resolve, reject) {
        var s
        s = document.createElement('script')
        s.src = src
        s.onload = resolve
        s.onerror = reject
        document.head.appendChild(s)
      })
    },
    randomString: (len, charSet) => {
      charSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
      var randomString = ''
      for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length)
        randomString += charSet.substring(randomPoz, randomPoz + 1)
      }
      return randomString
    },
    xDate() {
      var d = new Date()
      var n = new Date().toISOString()
      return new Date().toISOString().substr(0, 16)
    },
    isArabic(text) {
      var arregex = /[\u0600-\u06FF]/
      return arregex.test(text)
    },
    print: (elem) => {
      window.print()
      return false
    },
    goToUp: (scrollTime = 200) => {
      window.scrollTo(0, 0)
      // setTimeout(() => {
      //   (function smoothscroll() {
      //     var currentScroll =
      //       document.documentElement.scrollTop || document.body.scrollTop;
      //     if (currentScroll > 0) {
      //       window.requestAnimationFrame(smoothscroll);
      //       window.scrollTo(0, currentScroll - currentScroll / 5);
      //     }
      //   })();
      // }, scrollTime);
    },
    params: (obj) => {
      return Object.keys(obj)
        .map((k) => encodeURIComponent(k) + '=' + encodeURIComponent(obj[k]))
        .join('&')
    },
    numberFormat: (value, currency = '', fixed = 1) => {
      if (!value) return '0 ' + currency
      value =
        value != 0 && !isNaN(value)
          ? value.toLocaleString('ar-TN', { minimumFractionDigits: 0 }) +
            ' ' +
            currency
          : '0 ' + currency
      return value.split('.').join(' ').split(',').join('.')
    },
    moneyFormat: (value, currency = 'TND') => {
      if (!value) return '0 ' + currency
      value =
        value != 0 && !isNaN(value)
          ? value.toLocaleString('ar-TN', { minimumFractionDigits: 0 }) +
            ' ' +
            currency
          : '0 ' + currency
      return value.split('.').join(' ')
    },
    price: (price = 0, normal_price = 0, discount_price = 0) => {
      price = parseFloat(price)
      normal_price = parseFloat(normal_price)
      discount_price = parseFloat(discount_price)
      var del = normal_price > price ? normal_price : price
      if (discount_price > 0) {
        price = price - discount_price
      }
      if (price < 0) price = 0
      return del != price
        ? '<del class="mr-2 caption grey--text">' +
            moneyFormat(del) +
            '</del><span>' +
            moneyFormat(price) +
            '</span>'
        : moneyFormat(price)
    },
    rate: (num0, num1) =>
      Math.round(((Number(num0) - Number(num1)) * 100) / Number(num0)),
    sortAlpha: function (arrays, name = 'name') {
      return arrays.slice().sort(function (a, b) {
        return a[name].toLowerCase() > b[name].toLowerCase() ? 1 : -1
      })
    },
    sortAlphaItem: function (arrays, name = 'name') {
      return arrays.slice().sort(function (a, b) {
        return a.toLowerCase() > b.toLowerCase() ? 1 : -1
      })
    },
    getStorage: (name) => {
      return window.localStorage.getItem(name)
    },
    setStorage: (name, value) => {
      window.localStorage.setItem(name, value)
    },
    deleteStorage: (name) => {
      window.localStorage.removeItem(name)
    },
    setCookie: (name, value, minutes = 1) => {
      var expires = ''
      if (minutes) {
        var date = new Date()
        date.setTime(date.getTime() + minutes * 60 * 1000)
        expires = '; expires=' + date.toUTCString()
      }
      document.cookie = name + '=' + (value || '') + expires + '; path=/'
    },
    getCookie: (name) => {
      var nameEQ = name + '='
      var ca = document.cookie.split(';')
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i]
        while (c.charAt(0) == ' ') c = c.substring(1, c.length)
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length)
      }
      return null
    },
    eraseCookie: (name) => {
      document.cookie = name + '=; Max-Age=-99999999;'
    },
    sleep: (time) => {
      return new Promise((resolve) => setTimeout(resolve, time))
    },
    selectText: (containerid) => {
      if (document.selection) {
        // IE
        var range = document.body.createTextRange()
        range.moveToElementText(document.querySelector(containerid))
        range.select()
      } else if (window.getSelection) {
        range = document.createRange()
        range.selectNode(document.querySelector(containerid))
        window.getSelection().removeAllRanges()
        window.getSelection().addRange(range)
      }
      document.execCommand('copy')
    },
    download: (content, fileName, contentType) => {
      var a = document.createElement('a')
      var file = new Blob([content], { type: contentType })
      a.href = URL.createObjectURL(file)
      a.download = fileName
      a.click()
    },
    popup: (o) => {
      var e = null != window.screenLeft ? window.screenLeft : screen.left,
        t = null != window.screenTop ? window.screenTop : screen.top,
        n =
          (window.innerWidth
            ? window.innerWidth
            : document.documentElement.clientWidth
            ? document.documentElement.clientWidth
            : screen.width) /
            2 -
          250 +
          e,
        i =
          (window.innerHeight
            ? window.innerHeight
            : document.documentElement.clientHeight
            ? document.documentElement.clientHeight
            : screen.height) /
            2 -
          200 +
          t,
        r = window.open(
          o,
          'targetWindow',
          'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=500, height=400, top=' +
            i +
            ', left=' +
            n
        )
      window.focus && r.focus()
    },
  }
  inject('method', method)
}
