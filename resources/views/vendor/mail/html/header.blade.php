<tr class="top-head">
    <td class="left-section">
        <img src="http://forge.codigoforge.com/forge-flat-logo.png" alt="Forge" class="mail-logo">
    </td>
    <td class="right-section">
        <span class="secondary-text">CodigoForge</span>
    </td>
</tr>

<tr>
    <td class="header {{ isset($class)?$class:'' }}">
        <a href="{{ $url }}">
            {{ $slot }}
        </a>
    </td>
</tr>
