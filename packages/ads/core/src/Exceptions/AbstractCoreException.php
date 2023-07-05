<?php

namespace Ads\Core\Exceptions;

abstract class AbstractCoreException extends \Exception
{
    /**
     * Default error message.
     *
     * @var string
     */
    protected string $defaultErrorMessage = 'Произошла ошибка при обработке запроса. Обратитесь к разработчику.';

    public function handle(): array
    {
        $message = (($this->getMessage() === '' || !config('app.debug'))) ? __($this->defaultErrorMessage) : $this->getMessage();

        if (!config('app.debug') && config('app.log_level') === 'debug') {
            $data['_debug'] = $this->getMessage();
        }

        return response()->error($this->getCode(), $message, $data, $this->getCode());
    }
}
