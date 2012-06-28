$.tools.validator.fn("[data-equals]", "Value not equal with the $1 field", function(input) {
    var name = input.attr("data-equals"),
    field = this.getInputs().filter("[name=" + name + "]");
    return input.val() == field.val() ? true : [name];
});