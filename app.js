/**
 * Created by udit on 05/01/16.
 */

$( function() {

	var ToDoApiUrl = 'http://watchman.me/wp-json/wp/v2/';

	// to-do Model
	var ToDoItem = Backbone.Model.extend( {
		initialize: function() {
			this.category = '';
		},
		parse: function( item ) {
			Backbone.ajax( {
				dataType: 'json',
				method: 'get',
				url: ToDoApiUrl + 'todos/' + item.id + '/categories/',
				success: function( categories ) {
					if ( categories.length > 0 ) {
						category = categories[0];
						this.category = category.name;
					}
				}
			} );
			return item;
		}
	} );

	// To-Do Collection
	var ToDoList = Backbone.Collection.extend( {
		model: ToDoItem,
		url: ToDoApiUrl + 'todos/'
	} );

	var todos = new ToDoList;

	var ToDoItemView = Backbone.View.extend( {
		tagName: 'li',
		className: 'todo-item',
		render: function() {

		}
	} );

	var ToDoListView = Backbone.View.extend( {
		id: 'todo-list',
		render: function() {

		}
	} );

	var AppView = Backbone.View.extend( {
		id: 'todo-app',
		initialize: function() {
			//this.listenTo( todos, 'change',  )
			//this.listenTo( todos, 'all', this.render );
			todos.fetch();
		},
		render: function() {

		}
	} );

	var app = new AppView;
} );
