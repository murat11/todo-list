$(function() {
	'use strict';

	var $todoListForm = $('#todo-list-form');
	var $todoListName = $('#todo-list-name');
	var $participantsList = $('#participants-list');

	var onTodoListFormSubmit = function(event) {
		event.preventDefault();

		var url = 'api/lists';
		var data = serializeForm();

		$.ajax({
			method: 'POST',
			url: url,
			data: JSON.stringify(data),
			contentType: 'application/json',
			dataType: 'json'
		})
			.done(function() {
				alert('The todo list has been created successfully!');

				$todoListForm.find('input').val('');

				removeUnnecessaryRows();
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				alert(errorThrown);
			})
	};

	var onParticipantEmailKeyUp = function() {
		var $emptyRows = getEmptyRows();

		if ($emptyRows.length > 1) {
			removeUnnecessaryRows();
		}

		if ($emptyRows.length === 0) {
			createNewRow();
		}
	};

	var serializeForm = function() {
		var participants = [];

		$todoListForm.find('.participant-email').each(function(index, participantEmail) {
			if (participantEmail.value === '') {
				return true;
			}

			participants.push(participantEmail.value)
		});

		return {
			name: $todoListName.val(),
			participants: participants
		};
	};

	var createNewRow = function() {
		$('<div/>', {
			'html': [
				$('<input/>', {
					'type': 'email',
					'class': 'form-control participant-email',
					'placeholder': 'Participant Email',
					'value': ''
				})
			]
		})
			.appendTo($participantsList);
	};

	var removeUnnecessaryRows = function() {
		var $emptyRows = getEmptyRows();
		$emptyRows.not(':first').remove();
	};

	var getEmptyRows = function() {
		return $todoListForm.find('.participant-email').filter(function(index, participantEmail) {
			return participantEmail.value === '';
		});
	};

	$todoListForm
		.on('submit', onTodoListFormSubmit)
		.on('keyup', '.participant-email', onParticipantEmailKeyUp);

	createNewRow();
});
