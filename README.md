# Phalcon Validator

Easy validation for Phalcon Framework (in development)


### Available rules
- [x]  accepted
- [x]  alpha
- [x]  boolean
- [x]  email
- [x]  max
- [x]  min
- [x]  numeric
- [x]  required
- [ ]  unique
- [x]  url

### Examples
```php
# Fetch data from request

$postData = $this->request->getPost();

# Create new Validator, pass data, define rules and custom messages
# Also has errors messages by default

$validator = (new Validator)::make($postData, [
   'firstname' => 'required|min:2',
   'lastname'  => 'required|max:5',
   'email'     => 'required|email|unique:Users', //Multiple\Shared\Models\Users in modular app
   'password'  => 'required|min:6',
], [
   'email.required'     => 'Email is required',
   'email.email'        => 'Email has bad format',
   'email.unique'       => 'Email is not unique',
]);
```

### Advanced Usage
----

#### Rules - make your own class that will help you to validate data.

```
use Progsmile\Validator\Contracts\Rules\RulesInterface;

class ArraySuccessCheck implements RulesInterface
{
    private $params;

    public function isValid()
    {
        $field = $this->params[0];
        $value = $this->params[1]
        $table = $this->params[2];

        return isset($value['success']) ? true : false;
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        return "Array :field: must have an index 'success'"; # you can use ':value:' too
    }
}
```

Now you've created your own class, inject this class to the Validator class

```
$instance = new Validator(ArraySuccessCheck::class);

# or you can call injectClass() function
$instance->injectClass(ArraySuccessCheck::class);

$validator = $instance->make();

if ($validator->isValid() === false) {
    echo $validator->format();
}
```

#### Formatting - the best way to auto-reformat the returned array into your own style

The `$validator->format()`, by default, the messages will be formatted to html `<ul><li></li>...</ul>` element.

You can create your own class to format the array `$validator->messages()` into a well formed result.

```
use Progsmile\Validator\Contracts\Format\FormatInterface;

class MarkdownFormatter implements FormatInterface
{
    public function reformat($messages)
    {
        $ret = '#### Error Found';

        foreach ($messages as $field => $message) {

            foreach ($message as $content) {

                $ret .= "- [x] ".$content."**\n"
            }
        }

        return $ret;
    }
}
```

Then in to use this call, you must do this way:

```
$validator = new Validator;
# ... some code here...
echo $validator->format(MarkdownFormatter::class);
```

----

Project is just started and it is not stable yet, we love to have your fork requests.
