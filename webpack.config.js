const Encore = require('@symfony/webpack-encore');
const path = require('path');
const fs = require('fs');

const basePath = path.resolve(__dirname, './');
const assets_path = path.join(basePath, './private');
const output_path = path.join(basePath, './public');
const public_path = 'bundles/asdoriasyliusbulkeditplugin';
const js_path = path.join(assets_path, './js');
const sass_path = path.join(assets_path, './css');
const isProduction = Encore.isProduction();

Encore
  // empty the outputPath dir cd ../before each build
  .cleanupOutputBeforeBuild()

  // directory where all compiled assets will be stored
  .setOutputPath(output_path)

  .setPublicPath('/' + public_path)
  .setManifestKeyPrefix(public_path)
  .addEntry('admin-bulk-edit', [
    path.join(js_path, './admin-bulk-edit.js'),
    path.join(sass_path, './admin-bulk-edit.scss'),
  ])
 

  // allow sass/scss files to be processed
  .enableSassLoader()
  .enablePostCssLoader()
  .autoProvidejQuery()
  // allow legacy applications to use $/jQuery as a global variable
  // .autoProvidejQuery()

  .enableSourceMaps(!isProduction)

  .disableSingleRuntimeChunk()

  // create hashed filenames (e.g. app.abc123.css)
  .enableVersioning(false)
  .configureFilenames({
    js: '[name].min.js',
    css: '[name].min.css',
  })

;

config = Encore.getWebpackConfig();

module.exports = config;
