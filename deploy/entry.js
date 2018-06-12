module.exports = {
  playbook: {
    file: 'deploy/entry.yml',
    hosts: 'deploy/hosts',
    // hosts: ['193.112.127.136'],
    tasks: ['ping', 'sync'],
    node: 'knight',
    user: 'root',
    port: '22',
    debug: false,
    vars: {
      node: 'knight'
    }
  },
  scripts: ['npm run build'],
  slack: {
    project: 'knight',
    webhook: 'https://hooks.slack.com/services/T2AV0RV8E/B2UQU57HU/*****',
    channel: 'general',
    username: 'bug-dog'
  },
  git: false,
  debug: true,
};
