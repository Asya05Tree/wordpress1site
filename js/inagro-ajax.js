jQuery(document).ready(function ($) {
    let currentInstructions = [];
    let currentInstructionIndex = 0;

    loadCategories();

    function loadCategories() {
        $.ajax({
            url: inagro_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_categories',
                nonce: inagro_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    let categoriesList = $('#categories-list');
                    categoriesList.empty();
                    response.data.forEach(function (category) {
                        let categoryHtml = `
                            <button class="accordion" data-id="${category.id}">${category.name}</button>
                            <div class="panel">
                                <div class="subcategories-list"></div>
                            </div>
                        `;
                        categoriesList.append(categoryHtml);
                    });
                    setupAccordion();
                }
            }
        });
    }

    function setupAccordion() {
        $('.accordion').off('click').on('click', function () {
            $(this).toggleClass('active');
            let panel = $(this).next('.panel');

            if ($(this).hasClass('active')) {
                let categoryId = $(this).data('id');
                loadSubcategories(categoryId, panel.find('.subcategories-list'));
                panel.css('max-height', panel.prop('scrollHeight') + "px");
            } else {
                panel.css('max-height', null);
                $('#instruction-content').empty();
            }
        });
    }

    function loadSubcategories(categoryId, container) {
        $.ajax({
            url: inagro_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_subcategories',
                category_id: categoryId,
                nonce: inagro_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    container.empty();
                    if (response.data.length > 0) {
                        container.append('<button class="all-instructions" data-id="' + categoryId + '">Всі інструкції цього критерію</button>');
                        response.data.forEach(function (subcategory) {
                            container.append(`<button class="subcategory" data-id="${subcategory.id}">${subcategory.name}</button>`);
                        });
                        setupSubcategoryListeners();
                    }
                    loadInstructions(categoryId);
                    container.closest('.panel').css('max-height', container.closest('.panel').prop('scrollHeight') + "px");
                }
            }
        });
    }


    function setupSubcategoryListeners() {
        $('.subcategory, .all-instructions').off('click').on('click', function () {
            $('.subcategory, .all-instructions').removeClass('active');

            $(this).addClass('active');

            if ($(this).hasClass('all-instructions')) {
                loadInstructions($(this).data('id'));
            } else {
                loadInstructions(null, $(this).data('id'));
            }
        });
    }

    function loadInstructions(categoryId, subcategoryId = null) {
        let data = {
            action: 'get_instructions',
            nonce: inagro_ajax.nonce
        };

        if (subcategoryId) {
            data.subcategory_id = subcategoryId;
        } else if (categoryId) {
            data.category_id = categoryId;
        }

        $.ajax({
            url: inagro_ajax.ajax_url,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    currentInstructions = response.data;
                    currentInstructionIndex = 0;
                    updateInstructionDisplay();
                }
            }
        });
    }

    function updateInstructionDisplay() {
        let instruction = currentInstructions[currentInstructionIndex];
        let instructionContent = $('#instruction-content');
        instructionContent.empty();

        if (instruction) {
            instructionContent.html(`
                <h3>${instruction.name}</h3>
                <div class="instruction-content">${instruction.content}</div>
                <div class="instruction-files">
                    <a href="${instruction.file_url}" target="_blank">Скачати PDF</a>
                    <div class="qr-code-container"></div>
                </div>
                <button class="view-full-instruction" data-id="${instruction.id}">Подивитися інструкцію у новому вікні</button>
            `);

            $.ajax({
                url: inagro_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'generate_qr_code',
                    nonce: inagro_ajax.nonce,
                    url: instruction.file_url,
                    instruction_id: instruction.id
                },
                success: function (response) {
                    if (response.success) {
                        instructionContent.find('.qr-code-container').html(response.data);
                    }
                }
            });

            $('.view-full-instruction').on('click', function() {
                let instructionId = $(this).data('id');
                window.open(`/page-instructions/${instructionId}`, '_blank');
            });
        } else {
            instructionContent.html('<p>Немає інструкцій</p>');
        }

        updateSliderButtons();
    }

    function updateSliderButtons() {
        $('#prev-instruction').prop('disabled', currentInstructionIndex === 0);
        $('#next-instruction').prop('disabled', currentInstructionIndex === currentInstructions.length - 1);
    }

    $('#prev-instruction').click(function () {
        if (currentInstructionIndex > 0) {
            currentInstructionIndex--;
            updateInstructionDisplay();
        }
    });

    $('#next-instruction').click(function () {
        if (currentInstructionIndex < currentInstructions.length - 1) {
            currentInstructionIndex++;
            updateInstructionDisplay();
        }
    });

    $('#search-button').click(function () {
        let searchTerm = $('#search-input').val().trim().toLowerCase();
        if (searchTerm === '') {
            $('#search-error').text('Введіть назву інструкції.').show();
        } else {
            $('#search-error').hide();
            searchInstructions(searchTerm);
        }
    });

    function searchInstructions(term) {
        $.ajax({
            url: inagro_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'search_instructions',
                nonce: inagro_ajax.nonce,
                term: term
            },
            success: function (response) {
                if (response.success) {
                    currentInstructions = response.data.filter(instruction =>
                        instruction.name.toLowerCase().includes(term)
                    );
                    currentInstructionIndex = 0;
                    updateInstructionDisplay();

                    if (currentInstructions.length === 0) {
                        $('#instruction-content').html('<p>Інструкцій не знайдено</p>');
                    }
                }
            }
        });
    }
});