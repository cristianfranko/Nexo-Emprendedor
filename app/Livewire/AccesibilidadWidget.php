<?php

namespace App\Livewire;

use Livewire\Component;

class AccesibilidadWidget extends Component
{
    /**
     * Propiedad pública para recibir la clase de CSS que define
     * la posición del widget en pantallas de escritorio (lg).
     * @var string
     */
    public string $desktopPositionClass;

    /**
     * El método mount se ejecuta cuando el componente es creado.
     * Le damos un valor por defecto ('lg:left-6') si no se especifica
     * ninguna clase al llamar al componente.
     *
     * @param string $desktopPositionClass
     * @return void
     */
    public function mount(string $desktopPositionClass = 'lg:left-6')
    {
        $this->desktopPositionClass = $desktopPositionClass;
    }

    /**
     * Renderiza la vista del componente.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.accesibilidad-widget');
    }
}