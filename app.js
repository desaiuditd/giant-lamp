/**
 * Created by udit on 05/01/16.
 */

$( function() {

	var ToDoApiUrl = 'http://watchman.me/wp-json/wp/v2/';

	// to-do Model
	var ToDoItem = Backbone.Model.extend( {
		defaults: function() {
			return {
				category: ''
			};
		},
		parse: function( item ) {
			var self = this;
			Backbone.ajax( {
				dataType: 'json',
				method: 'get',
				url: ToDoApiUrl + 'todos/' + item.id + '/categories/',
				success: function( categories ) {
					if ( categories.length > 0 ) {
						var category = categories[0];
						self.set( "category", category.name );
					}
				}
			} );
			return item;
		}
	} );

	// To-Do Collection
	var ToDoList = Backbone.Collection.extend( {
		model: ToDoItem,
		url: ToDoApiUrl + 'todos/',
		resetTodos: function() {
			var self = this;
			this.fetch( {
				reset: true,
				success: function( collection, response, options ) {
					self.trigger( 'successOnFetch' );
				}
			} );
		}
	} );

	var todos = new ToDoList;

	var ToDoItemView = Backbone.View.extend( {
		tagName: 'li',
		className: 'todo-item',
		template: _.template( $( '#item-template' ).html() ),
		initialize: function() {
			var self = this;
			this.model.on( 'change', function( model ) {
				self.$el.toggleClass( 'done', 'done' === model.get( 'category' ) );
				self.$( 'input.toggle' ).attr( 'checked', 'done' === model.get( 'category' ) );
				self.$( 'input.toggle' ).prop( 'checked', 'done' === model.get( 'category' ) );
			} );
		},
		events: {
			"click a.destroy" : "clear",
			"dblclick .view"  : "edit",
			//"blur .edit"      : "close"
		},
		render: function() {
			this.$el.html( this.template( this.model.toJSON() ) );
			//this.$el.toggleClass( 'done', this.model.get( 'done' ) );
			this.input = this.$( '.edit' );
			return this;
		},
		// Switch this view into `"editing"` mode, displaying the input field.
		edit: function () {
			this.$el.addClass( "editing" );
			this.input.focus();
		},
		// Close the `"editing"` mode, saving changes to the to-do.
		close: function() {
			var value = this.input.val();
			//if (!value) {
				//this.clear();
			//} else {
				this.model.save({title: value});
				this.$el.removeClass("editing");
			//}
		}
		// Remove the item, destroy the model.
		//clear: function() {
		//	this.model.destroy();
		//}
	} );

	var AppView = Backbone.View.extend( {
		id: 'todo-app',
		initialize: function() {

			this.todolistel = $( '#todo-list' );

			// fetch the existing data from API. This is coming via ajax from API, since we don't have server side script to render it on page load.
			todos.resetTodos();

			this.listenTo( todos, 'successOnFetch', this.render );
		},
		render: function( options ) {
			todos.each( this.addOne, this );
		},
		addOne: function( todo ) {
			var view = new ToDoItemView( { model: todo } );
			this.todolistel.append( view.render().el );
		}
	} );

	var app = new AppView;
} );
