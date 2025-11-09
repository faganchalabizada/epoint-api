<?php
namespace FaganChalabizada\Epoint\Response;

class CreateWidgetResponse extends APIResponse
{

    /**
     * Check if the widget creation was successful.
     *
     * @return bool True if the status is "success", false otherwise.
     */
    public function isSuccess(): bool
    {
        return $this->data['status'] === 'success';
    }

    // Get the Apple&Google pay widget URL
    public function getWidgetURL(): ?string
    {
        return $this->data['widget_url'];
    }

}