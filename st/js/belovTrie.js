$(document).ready(function() {
    //TwosphereIndexer.run();
});

var TwosphereIndexer = {
    materials: null,
    trie: [],
    index: function() {
        if (!this.materials) {
            console.log("Index not created");
            return false;
        }

        var items = this.materials.items;
        for (var el in items) {
            if (!items.hasOwnProperty(el)) continue;
            var name = items[el].name
                .replace(/([.,-])/gi, ' ')
                .replace(/\s(?=\s)/i, '')
                .trim();
            var words = name.split(' ');
            //todo: create trie
        }

        console.log("Index created: ", this.trie);
    },
    run: function() {
        var obj = {
            url: "http://twosphere.ru/methods/materials.", //!todo
            data: {}
        };
        Ajax.get(obj, function(res) {
            if (res && res.items.length) {
                TwosphereIndexer.materials = res;
                TwosphereIndexer.index();
            }
        })
    }
};