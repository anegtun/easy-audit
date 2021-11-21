/**
 * MAQINT Custom Configuration.
 * @type Object
 */
customConfig = {
  api: "siteApi",
  browser: {
    showSupportWarning: true,
    minVersion: {
      msie: "10",
      msedge: "1",
      chrome: "25",
      firefox: "30",
      opera: "40"
    }
  },
  lang: {
    default: false,
    enableLangSelector: true
  },
  env: {
    envName: 'CONTORNA DE PROBAS',
    showEnvDisplay: true
  },
  pages: {
    home: "index.html"
  },
  ui: {
    leftMenu: {
      defaultState: 1, //0:close, 1:open
      autoActive: false
    },
    structure: {
      defaultState: 'main' //'main', 'nom', 'nlm', 'ntm', 'nph'
    },
    highContrast: {
      defaultState: false
    }
  },
  form: {
    dateTime : {
      transformFields: true,
      format: { // http://momentjs.com/docs/#/displaying/format/
        date: "DD-MM-YYYY",
        time: "HH:mm"
      },
      placeholder: {
        date: "dd-mm-aaaa",
        time: "--:--"
      }
    }
  }
};