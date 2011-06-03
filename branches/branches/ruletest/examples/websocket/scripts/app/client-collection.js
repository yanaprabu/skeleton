/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(function(){

	window.Client = Backbone.Model.extend({
		
	});

	window.ClientList = Backbone.Collection.extend({
		model: Client
	});

	window.ClientView = Backbone.View.extend({
		tagName: 'li',

		template: _.template($('#client-template').html()),

		events: {
			'dblclick li.client'	: 'edit'
		},

		initialize: function () {
			_.bindAll(this, 'render', 'close');
			this.model.bind('change', this.render);
			this.model.view = this;
		},

		setName: function () {
			
		}
	})
})