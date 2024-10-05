// src/plugins/utilsPlugin.js
import * as utils from '@/utils';

export default {
  install(app) {
    // Loop through all exported functions from utils and make them globally accessible
    for (const key in utils) {
      app.config.globalProperties[`$${key}`] = utils[key];
    }
  }
};
