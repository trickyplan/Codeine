$.tools.validator.fn("[data-minlength]", {ru: "Недостаточно символов"}, function(input) {
    var size = input.attr("data-minlength");
    return (input.attr('value').length >= size)
});