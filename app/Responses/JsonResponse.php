<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/25/19
 * Time: 3:50 PM
 */

namespace App\Responses;

/**
 * Class JsonResponse
 * Response Object for app
 *
 * Response Format
 * {
 *      'success': true|false,
 *      'data': [],
 *      'error': ''
 * }
 */
class JsonResponse implements \JsonSerializable
{
    public $data = [];

    public $message = '';

    public $error = [];

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct($message = '', array $data = [], $errors = [])
    {
        $this->message = $message;
        if($this->shouldBeJson($data)) {
            $this->data = $data;
        }
        $this->errors = $errors;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->message,
            'errors' => $this->errors,
            'data' => $this->data
        ];
    }

    /**
     * Determine if the given content should be turned into JSON.
     *
     * @param  mixed  $content
     * @return bool
     */
    private function shouldBeJson($content): bool
    {
        return $content instanceof Arrayable ||
            $content instanceof Jsonable ||
            $content instanceof \ArrayObject ||
            $content instanceof \JsonSerializable ||
            is_array($content);
    }
}
