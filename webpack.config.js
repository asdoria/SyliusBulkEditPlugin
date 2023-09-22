const Encore = require('@symfony/webpack-encore');
const path = require('path');
const fs = require('fs');

const basePath = path.resolve(__dirname, './');
const assets_path = path.join(basePath, './private');
const output_path = path.join(basePath, './public');
const public_path = 'bundles/asdoriasyliusquoterequestplugin';
const js_path = path.join(assets_path, './js');
const isProduction = Encore.isProduction();

Encore
  // empty the outputPath dir cd ../before each build
  .cleanupOutputBeforeBuild()

  // directory where all compiled assets will be stored
  .setOutputPath(output_path)

  .setPublicPath('/' + public_path)
  .setManifestKeyPrefix(public_path)
  .addEntry('shop-bulk-edit', [
    path.join(js_path, './shop-bulk-edit.js'),
  ])
  .addEntry('shop-add-to-quote', [
    path.join(js_path, './shop-add-to-quote.js'),
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
  })

;

config = Encore.getWebpackConfig();

module.exports = config;
