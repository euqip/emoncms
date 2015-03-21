
var org = {

  'get':function()
  {
    var result = {};
    $.ajax({ url: path+"org/get.json", dataType: 'json', async: false, success: function(data) {result = data;} });
    return result;
  },

  'set':function(data)
  {
    var result = {};
    $.ajax({
      type: "POST",
      url: path+"org/set.json",
      data: "&data="+JSON.stringify(data),
      dataType: 'json',
      async: false,
      success: function(data) {result = data;} });
    return result;
  },

  'newapikeywrite':function()
  {
    var result = {};
    $.ajax({ url: path+"org/newapikeywrite.json", dataType: 'json', async: false, success: function(data) {result = data;} });
    return result;
  },

  'newapikeyread':function()
  {
    var result = {};
    $.ajax({ url: path+"org/newapikeyread.json", dataType: 'json', async: false, success: function(data) {result = data;} });
    return result;
  }

}

