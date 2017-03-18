var casper = require('casper').create({
    pageSettings: {
        userAgent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36'
    }
});

casper.start('https://backstage.taboola.com/backstage/login');

casper.waitForSelector('#loginForm', function() {
    this.fillSelectors('#loginForm', {
        'input[name=j_username]': casper.cli.options.username,
        'input[name=j_password]': casper.cli.options.password
    });
    this.click('#login');
},15000);

casper.then(function() {
    this.echo(JSON.stringify(casper.page.cookies));
});

casper.run();







/*
var phantom = require('phantom');
var _ph, _page, _outObj;

phantom.create().then(ph => {
    _ph = ph;
    return _ph.createPage();
}).then(page => {
    _page = page;
    page.setting('userAgent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');
    return _page.open('https://backstage.taboola.com/backstage/login');
}).then(status => {
    //console.log(status);
    return _page.property('content')
}).then(content => {
    //_page.property('content')
    _page.evaluate(function() {
        setTimeout(1000);
        var els = document.getElementById('loginForm').elements;
        return {
            serverTime: els['serverTime'].value,
            _csrf: els['_csrf'].value,
            redir: els['redir'].value,
            sig: els['sig'].value,
            cookies: document.cookie
        };
    }).then(function(data) {
        console.log(JSON.stringify(data));
        _page.close();
        _ph.exit();
    });
}).catch(e => console.log(e));

*/