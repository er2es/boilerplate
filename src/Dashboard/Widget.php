<?php

namespace Sebastienheyd\Boilerplate\Dashboard;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Auth;

abstract class Widget
{
    protected $slug;
    protected $label;
    protected $description;
    protected $permission;
    protected $size = 'md';
    protected $width;
    protected $view;
    protected $editView;
    protected $parameters = [];
    private $settings = [];
    private $values = [];

    public static function getColors()
    {
        return [
            'primary'   => __('Blue'),
            'secondary' => __('Grey'),
            'success'   => __('Green'),
            'danger'    => __('Red'),
            'warning'   => __('Orange'),
            'info'      => __('Cyan'),
            'light'     => __('Light'),
            'dark'      => __('Dark'),
        ];
    }

    /**
     * Method called before any rendering. You can assign values here.
     *
     * @return void
     */
    public function make()
    {
    }

    /**
     * Assign values to the view. This will overload the default parameters and settings.
     *
     * @param  string  $name
     * @param  mixed  $value
     * @return $this
     */
    protected function assign($name, $value = null)
    {
        if (is_string($name)) {
            if (empty($value)) {
                return $this;
            }

            $name = [$name => $value];
        }

        $this->values = array_merge($this->values, $name);

        return $this;
    }

    /**
     * Change a widget setting. This will overload the default parameters.
     *
     * @param  string  $name
     * @param  mixed  $value
     * @return $this
     */
    public function set($name, $value = null)
    {
        if (is_string($name)) {
            if (empty($value)) {
                return $this;
            }

            $name = [$name => $value];
        }

        $this->settings = array_merge($this->settings, $name);

        return $this;
    }

    /**
     * Get in order a value, a setting or a default parameter.
     *
     * @param  string  $name
     * @return mixed|null
     */
    public function get($name)
    {
        return $this->values[$name] ?? $this->settings[$name] ?? $this->parameters[$name] ?? null;
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public function getSettings()
    {
        foreach (auth()->user()->setting('dashboard', config('boilerplate.dashboard.widgets')) as $widgetParameters) {
            [$widgetSlug, $settings] = [array_key_first($widgetParameters), $widgetParameters[array_key_first($widgetParameters)]];
            if ($widgetSlug === $this->slug) {
                return array_merge($settings, $this->settings);
            }
        }

        return $this->settings;
    }

    /**
     * Render the widget with the given values, settings and default parameters.
     *
     * @return string
     */
    public function render()
    {
        $parameters = array_merge($this->parameters, $this->settings, $this->values);
        $parameters['widget'] = $this;

        return view($this->view, $parameters)->render();
    }

    /**
     * Get the edit view name or false.
     *
     * @return string|false
     */
    public function getEditView()
    {
        return $this->editView ?? false;
    }

    /**
     * Render the edit view.
     *
     * @param  array  $values  Values from the posted form or from the user parameters.
     * @return string
     */
    public function renderEdit($values = [])
    {
        $parameters = array_merge($this->parameters, $values);

        return view($this->editView, $parameters)->render();
    }

    /**
     * Returns true if current widget can be used by the current user.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        if (empty($this->view)) {
            return false;
        }

        return empty($this->permission) || Auth::user()->ability('admin', $this->permission);
    }

    /**
     * Returns true if the current widget is editable.
     *
     * @return bool
     */
    public function isEditable()
    {
        return ! empty($this->parameters) && ! empty($this->editView);
    }

    /**
     * Returns default parameters only.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Default getter.
     *
     * @param  $prop
     * @return array|Application|Translator|\Illuminate\Foundation\Application|int[]|string|null
     */
    public function __get($prop)
    {
        if ($prop === 'label' || $prop === 'description') {
            return __($this->{$prop}) ?: static::class.' → '.$prop;
        }

        if ($prop === 'width' && empty($this->width)) {
            $sizes = [
                'xxs' => ['sm' => 4, 'md' => 4, 'xl' => 2, 'xxl' => 2],
                'xs'  => ['sm' => 6, 'md' => 6, 'xl' => 4, 'xxl' => 3],
                'sm'  => ['sm' => 12, 'md' => 6, 'xl' => 6, 'xxl' => 4],
                'md'  => ['sm' => 12, 'md' => 6, 'xl' => 6, 'xxl' => 6],
                'xl'  => ['sm' => 12, 'md' => 12, 'xl' => 8, 'xxl' => 8],
                'xxl' => ['sm' => 12, 'md' => 12, 'xl' => 12, 'xxl' => 12],
            ];

            return $sizes[$this->size] ?? ['sm' => 6, 'md' => 6, 'xl' => 4, 'xxl' => 3];
        }

        if (property_exists($this, $prop)) {
            return $this->{$prop};
        }

        return null;
    }
}
