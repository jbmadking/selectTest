$(document).ready(function () {

    let educationLoaded = false;
    let selectList = $('#education-list');
    let saveButton = $('#saveSelection');
    let allOptions = [];

    selectList.on('click', function (e) {
        if (educationLoaded) {
            return;
        }
        loadEducation(allOptions);
        educationLoaded = true;
    });

    selectList.on('change', function (e) {

        $('#education-list option').each(function (key, element) {
            $(element).removeClass('selected')
        });
        $('#education-list option:selected').addClass('selected');
    });

    saveButton.on('click', function (e) {
        e.preventDefault();

        saveSelection(selectList.val(), $('#education-list option:selected').text(), allOptions);
    });
});

function loadEducation(allOptions) {
    $.get('http://localhost/education', function (data, status) {

        for (let weight in data) {
            let optionText = data[weight];
            const isDisabled = optionText.includes('(disabled)');
            let disabledOption = '';

            if (isDisabled) {
                disabledOption = 'disabled=disabled class="disabled"';
                optionText = optionText.replace(' (disabled)', '');
            }

            allOptions.push({weight: weight, value: optionText});

            $('#education-list').append(`<option value="${weight}" ${disabledOption}>${optionText}</option>`);
            $('#education-list').addClass('focused');
            $('#hidden-select').append(`<option value="${weight}" ${disabledOption}>${optionText}</option>`);
        }
    });
}

function saveSelection(selectedIndex, selectedValue, allOptions) {
    $.post(
        'http://localhost/education',
        {
            itemList: allOptions,
            selectedItem: {weight: selectedIndex, value: selectedValue}
        },
        function (data) {
            console.log(data);
            for (const key in data) {
                $('#' + key).text( key + ': ' + data[key]);
            }
        });
}
