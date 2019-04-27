import develop from './develop';
import production from './production';

let config = {
  version: '0.1.2'
};

const env = process.env.NODE_ENV;
console.log(process.env.NODE_ENV)
if(env === 'production') {
  config = Object.assign(config, production);
} else {
  config = Object.assign(config, develop);
}

console.log(config)

export default config;
