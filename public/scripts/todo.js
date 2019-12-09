$(function() {
	'use strict';
	
	Handlebars.registerHelper('eq', function(a, b, options) {
		return a === b ? options.fn(this) : options.inverse(this);
	});
	
	var ENTER_KEY = 13;
	var ESCAPE_KEY = 27;
	
	var util = {
		uuid: function() {
			var i;
			var random;
			var uuid = '';
			
			for (i = 0; i < 32; i++) {
				random = Math.random() * 16 | 0;
				if (i === 8 || i === 12 || i === 16 || i === 20) {
					uuid += '-';
				}
				uuid += (i === 12 ? 4 : (i === 16 ? (random & 3 | 8) : random)).toString(16);
			}
			
			return uuid;
		},
		pluralize: function(count, word) {
			return count === 1 ? word : word + 's';
		},
		getParam: function getQueryParams(query) {
			query = query.split('+').join(' ');
			
			var params = {},
				tokens,
				re = /[?&]?([^=]+)=([^&]*)/g;
			
			while (tokens = re.exec(query)) {
				params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
			}
			
			return params;
		}
	};
	
	var App = {
		init: function() {
			var list = util.getParam(location.search).list;
			
			if (!list) {
				alert('No "list" GET parameter available.');
				location.href = 'index.html';
				return;
			}
			
			var url = 'api.php/lists/' + list + '/todos';
			
			$.ajax({
				method: 'GET',
				url: url,
				dataType: 'json'
			})
				.done(function(response) {
					this.todos = response || [];
					this.todoTemplate = Handlebars.compile($('#todo-template').html());
					this.footerTemplate = Handlebars.compile($('#footer-template').html());
					this.bindEvents();
					
					new Router({
						'/:filter': function(filter) {
							this.filter = filter;
							this.render();
						}.bind(this)
					}).init('/all');
				}.bind(this))
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown)
				});
		},
		bindEvents: function() {
			$('.new-todo').on('keyup', this.create.bind(this));
			$('.toggle-all').on('change', this.toggleAll.bind(this));
			$('.footer').on('click', '.clear-completed', this.destroyCompleted.bind(this));
			$('.footer').on('click', '.delete-list', this.deleteList.bind(this));
			$('.todo-list')
				.on('change', '.toggle', this.toggle.bind(this))
				.on('dblclick', 'label', this.editingMode.bind(this))
				.on('keyup', '.edit', this.editKeyup.bind(this))
				.on('focusout', '.edit', this.update.bind(this))
				.on('click', '.destroy', this.destroy.bind(this));
		},
		render: function() {
			var todos = this.getFilteredTodos();
			$('.todo-list').html(this.todoTemplate(todos));
			$('.main').toggle(todos.length > 0);
			$('.toggle-all').prop('checked', this.getActiveTodos().length === 0);
			this.renderFooter();
			$('.new-todo').focus();
		},
		renderFooter: function() {
			var todoCount = this.todos.length;
			var activeTodoCount = this.getActiveTodos().length;
			var template = this.footerTemplate({
				activeTodoCount: activeTodoCount,
				activeTodoWord: util.pluralize(activeTodoCount, 'item'),
				completedTodos: todoCount - activeTodoCount,
				filter: this.filter
			});
			
			$('.footer').toggle(todoCount > 0).html(template);
		},
		toggleAll: function(e) {
			var list = util.getParam(location.search).list;
			var isChecked = $(e.target).prop('checked');
			var url = 'api.php/lists/' + list + '/todos';
			var data = {
				completed: isChecked
			};
			
			$.ajax({
				method: 'PATCH',
				url: url,
				data: JSON.stringify(data),
				contentType: 'application/json',
				dataType: 'json'
			})
				.done(function() {
					this.todos.forEach(function(todo) {
						todo.completed = isChecked;
					});
					this.render();
				}.bind(this))
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown)
				});
		},
		getActiveTodos: function() {
			return this.todos.filter(function(todo) {
				return !todo.completed;
			});
		},
		getCompletedTodos: function() {
			return this.todos.filter(function(todo) {
				return todo.completed;
			});
		},
		getFilteredTodos: function() {
			if (this.filter === 'active') {
				return this.getActiveTodos();
			}
			
			if (this.filter === 'completed') {
				return this.getCompletedTodos();
			}
			
			return this.todos;
		},
		destroyCompleted: function() {
			var list = util.getParam(location.search).list;
			var url = 'api.php/lists/' + list + '/todos?completed';
			$.ajax({
				method: 'DELETE',
				url: url,
				dataType: 'json'
			})
				.done(function() {
					this.todos = this.getActiveTodos();
					this.render();
				}.bind(this))
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown)
				});
		},
		// accepts an element from inside the `.item` div and
		// returns the corresponding index in the `todos` array
		getIndexFromEl: function(el) {
			var id = $(el).closest('li').data('id');
			var todos = this.todos;
			var i = todos.length;
			
			while (i--) {
				if (todos[i].id === id) {
					return i;
				}
			}
		},
		create: function(e) {
			var $input = $(e.target);
			var val = $input.val().trim();
			
			if (e.which !== ENTER_KEY || !val) {
				return;
			}
			
			var list = util.getParam(location.search).list;
			var url = 'api.php/lists/' + list + '/todos';
			var data = {
				id: util.uuid(),
				title: val,
				completed: false
			};
			
			$.ajax({
				method: 'POST',
				url: url,
				data: JSON.stringify(data),
				contentType: 'application/json',
				dataType: 'json'
			})
				.done(function() {
					this.todos.push(data);
					
					$input.val('');
					
					this.render();
				}.bind(this))
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown)
				});
		},
		toggle: function(e) {
			var $el = $(e.target);
			var $listItem = $el.closest('li');
			var list = util.getParam(location.search).list;
			var url = 'api.php/lists/' + list + '/todos/' + $listItem.data('id');
			var data = {
				id: $listItem.data('id'),
				title: $listItem.find('input:text').val(),
				completed: $el.prop('checked')
			};
			
			$.ajax({
				method: 'PUT',
				url: url,
				data: JSON.stringify(data),
				contentType: 'application/json',
				dataType: 'json'
			})
				.done(function() {
					var i = this.getIndexFromEl(e.target);
					this.todos[i].completed = !this.todos[i].completed;
					this.render();
				}.bind(this))
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown)
				});
		},
		editingMode: function(e) {
			var $input = $(e.target).closest('li').addClass('editing').find('.edit');
			// puts caret at end of input
			var tmpStr = $input.val();
			$input.val('');
			$input.val(tmpStr);
			$input.focus();
		},
		editKeyup: function(e) {
			if (e.which === ENTER_KEY) {
				e.target.blur();
			}
			
			if (e.which === ESCAPE_KEY) {
				$(e.target).data('abort', true).blur();
			}
		},
		update: function(e) {
			var el = e.target;
			var $el = $(el);
			var val = $el.val().trim();
			
			if ($el.data('abort')) {
				$el.data('abort', false);
				this.render();
			} else if (!val) {
				this.destroy(e);
			} else {
				var list = util.getParam(location.search).list;
				var $listItem = $el.closest('li');
				var url = 'api.php/lists/' + list + '/todos/' + $listItem.data('id')
				var data = {
					id: $listItem.data('id'),
					title: val,
					completed: $listItem.hasClass('completed')
				};
				
				$.ajax({
					method: 'PUT',
					url: url,
					data: JSON.stringify(data),
					contentType: 'application/json',
					dataType: 'json'
				})
					.done(function() {
						this.todos[this.getIndexFromEl(el)].title = val;
						this.render();
					}.bind(this))
					.fail(function(jqXHR, textStatus, errorThrown) {
						alert(errorThrown)
					});
			}
		},
		destroy: function(e) {
			var $listItem = $(e.target).closest('li');
			var list = util.getParam(location.search).list;
			var url = 'api.php/lists/' + list + '/todos/' + $listItem.data('id')
			
			$.ajax({
				method: 'DELETE',
				url: url
			})
				.done(function() {
					this.todos.splice(this.getIndexFromEl(e.target), 1);
					this.render();
				}.bind(this))
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown)
				});
		},
		deleteList: function(e) {
			e.preventDefault();
			
			if (!confirm('Are you sure that you want to delete this list?')) {
				return;
			}
			
			var list = util.getParam(location.search).list;
			
			var url = 'api.php/lists/' + list;
			
			$.ajax({
				method: 'DELETE',
				url: url,
				dataType: 'json'
			})
				.done(function() {
					location.href = 'index.html';
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown)
				});
		}
	};
	
	App.init();
});
