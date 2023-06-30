<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObatResource extends JsonResource
{
    // Membuat Variabel Public
    public $status;
    public $message;
    public $pagination;

    // Membuat Constructor
    public function __construct($status, $message, $resource, $pagination)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->pagination = $pagination;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
            'pagination' => $this->pagination
        ];
    }
}
