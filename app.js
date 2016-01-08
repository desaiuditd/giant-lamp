/**
 * Created by udit on 05/01/16.
 */

$( function() {

	var ToDoApiUrl = 'http://watchman.me/wp-json/wp/v2/';

	// to-do Model
	var ToDoItem = Backbone.Model.extend( {
		parse: function( item ) {
			var self = this;
			Backbone.ajax( {
				dataType: 'json',
				method: 'get',
				url: ToDoApiUrl + 'todos/' + item.id + '/categories/',
				success: function( categories ) {
					if ( categories.length > 0 ) {
						var category = categories[0];
						self.set( { category: category.name } );
						self.collection.trigger( 'updateCategory' );
					}
					console.log(self);
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
		render: function() {
			this.$el.html( this.template( this.model.toJSON() ) );
			//this.$el.toggleClass( 'done', this.model.get( 'done' ) );
			this.input = this.$( '.edit' );
			return this;
		}
	} );

	var ToDoListView = Backbone.View.extend( {
		id: 'todo-list',
		render: function() {
			var self = this;
			_.each( this.collection.models, function( item, key ) {
				self.$el.append( item.render().el );
			} );
		}
	} );

	var AppView = Backbone.View.extend( {
		id: 'todo-app',
		initialize: function() {
			this.todolistel = $( '#todo-list' );
			//this.listenTo( todos, 'change',  )
			//this.listenTo( todos, 'all', this.render );

			// fetch the existing data from API. This is coming via ajax from API, since we don't have server side script to render it on page load.
			todos.resetTodos();

			this.listenTo( todos, 'successOnFetch', this.render );
			this.listenTo( todos, 'updateCategory', this.updateCategory );
		},
		render: function( options ) {
			todos.each( this.addOne, this );
		},
		addOne: function( todo ) {
			var view = new ToDoItemView( { model: todo } );
			this.todolistel.append( view.render().el );
		},
		updateCategory: function() {
			console.log(todos.models);
		}
	} );

	var app = new AppView;
} );
