dependencies = {
    "action": "release",
    "optimize": "shrinksafe",
    "layerOptimize": "shrinksafe",
    "copyTests": false,
    "loader": "default",
    "cssOptimize": "comments",
    "releaseName": "custom",
    "layers": [{"name": "custom.main", "layerDependencies": [], "dependencies": ["custom.main"]}],
    "prefixes": [["custom", "../custom"], ["dojox", "../dojox"], ["dijit", "../dijit"]]
};