const { getDataConnect, validateArgs } = require('firebase/data-connect');

const connectorConfig = {
  connector: 'default',
  service: 'sosmed_php',
  location: 'us-central1'
};
exports.connectorConfig = connectorConfig;

