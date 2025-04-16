# 🧩 FlyUx Bundle – Inhaltsstruktur ohne Artikel-Ebene

Dieses Bundle erweitert Contao um eine neue Inhaltsstruktur, bei der die klassische Artikel-Ebene (`tl_article`) entfällt. Inhalte werden direkt auf Seitenebene verwaltet – das reduziert Komplexität und bringt neue Flexibilität in der Darstellung und Bearbeitung im Backend.

## 1. 🚫 Artikel-Ebene entfernt

Contao nutzt standardmäßig eine Zwischenebene namens „Artikel“ (`tl_article`), um Inhalte (`tl_content`) einer Seite zuzuordnen. In diesem Bundle wurde diese Ebene entfernt – `tl_content` verweist direkt auf `tl_page`.

**Vorgehen:**

- Migration beim Bundle-Setup verschiebt `tl_content`-Datensätze von Artikeln auf Seiten (`tl_content.pid = tl_article.pid`)
- `ptable` wird zu `tl_page`, `inColumn` bleibt erhalten
- Die Artikel-Tabelle (`tl_article`) kann nach der Migration entfernt werden
- Backend-Ansicht wurde angepasst, um Inhalte direkt unter Seiten zu verwalten

## 2. 🖼️ Media-View – Kachelansicht von Bildern im Backend

Ein neuer Mediamanager erlaubt die Verwaltung von Bildern in einer Kachelansicht – benutzerfreundlich und übersichtlich.

**Technische Details:**

- ⚙️ **Eigene `DC_Media`-Klasse**, die `DC_Folder` erweitert
- 🖼️ Bilder werden als Thumbnails in einer Kachelansicht dargestellt
- 🛠️ Thumbnails werden über einen eigenen `ImageResizer`-Service erstellt und gecached
- 🪟 Klick auf ein Bild öffnet ein **Modal** mit Details:
  - Pfad
  - Bildgröße
  - Bearbeitungsoptionen
- 🔍 Suchfunktion ist vorbereitet, aber noch nicht vollständig umgesetzt

## 3. 🧲 Drag & Drop im Inhaltsbereich

Die Inhalte (`tl_content`) lassen sich über ein intuitives Drag-&-Drop-Interface neu sortieren – auch spaltenübergreifend.

**Features:**

- 🎯 Sortierung basiert auf dem `sorting`-Feld in 128er-Schritten
- 📦 Per JavaScript können Inhalte innerhalb einer Spalte verschoben werden
- 🔀 Spaltenwechsel ist ebenfalls möglich – dabei wird `inColumn` aktualisiert
- 🔄 Neue Sortierung wird via AJAX an das Backend übertragen
- 🔐 Berechtigungen und Sichtbarkeit werden weiterhin beachtet

## 4. Be-Grid: Backend-Vorschau wie im Frontend

Im `tl_layout` wurde das Feld `be_grid` hinzugefügt.

- Redakteure können ein spezielles Backend-Stylesheet auswählen
- Das Stylesheet imitiert das Frontend-Grid
- Inhaltselemente erscheinen im Backend wie im Frontend angeordnet
- Unterstützt klares visuelles Feedback bei Drag-&-Drop

## ✅ Fazit

Mit diesem Bundle wurde die klassische Struktur von Contao gezielt verändert – zugunsten einer intuitiveren und moderneren Benutzererfahrung. Die Entfernung der Artikel-Ebene ermöglicht eine klarere Hierarchie, während die neuen Medien- und Inhaltsfunktionen das Backend deutlich aufwerten.

---

**Hinweis:** Diese Erweiterung richtet sich an erfahrene Contao-Nutzer. Sie verändert zentrale Abläufe des CMS und sollte daher nur gezielt und mit entsprechender Kenntnis eingesetzt werden.

