var TemplateManager = (function ($, Handlebars) {

    var o = {};
    o.templates = {};

    o.get = function (id, callback) {
        // Can we find this template in the cache?
        if (o.templates[id]) {

            // Yes? OK, lets call our callback function and return.
            return callback(o.templates[id]);
        }

        // Otherwise, lets load it up. We'll build our URL based on the ID passed in.
        var url = base_url + '/template/' + id + '.php';
        //console.log(url);
        //var promise = $.Deferred();

        $.get(url, function (template) {
            // `template` is a string of HTML loaded via `$.ajax`. So here, we
            // can take the opportunity to pre-compile it for performance. When we 
            // pre-compile a template, it returns a function that we can store in our 
            // cache for future use.
            var tmp = Handlebars.compile(template.toString());
            o.templates[id] = tmp;
            callback(tmp);
        }, 'text');

    };
    return o;


})(jQuery, Handlebars);
