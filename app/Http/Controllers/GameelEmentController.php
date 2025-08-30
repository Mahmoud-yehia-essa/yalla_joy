<?php

namespace App\Http\Controllers;

use App\Models\GameElement;
use Illuminate\Http\Request;

class GameelEmentController extends Controller
{
    // عرض كل عناصر اللعبة
    public function allGameElement()
    {
        $elements = GameElement::latest()->get();
        return view('backend.game_element.all_game_element', compact('elements'));
    }

    // فورم إضافة عنصر
    public function addGameElement()
    {
        return view('backend.game_element.add_game_element');
    }

    // تخزين عنصر جديد
    public function storeGameElement(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        GameElement::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
        ]);

        return redirect()->route('all.game.element')->with('success', 'تمت إضافة العنصر بنجاح');
    }

    // عرض فورم التعديل
    public function editGameElement($id)
    {
        $element = GameElement::findOrFail($id);
        return view('backend.game_element.edit_game_element', compact('element'));
    }

    // حفظ التعديل
    public function editGameElementStore(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:game_elements,id',
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $element = GameElement::findOrFail($request->id);
        $element->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
        ]);

        return redirect()->route('all.game.element')->with('success', 'تم تحديث العنصر بنجاح');
    }

    // حذف عنصر
    public function deleteGameElement($id)
    {
        GameElement::findOrFail($id)->delete();
        return redirect()->route('all.game.element')->with('success', 'تم حذف العنصر بنجاح');
    }

    // إخفاء عنصر
    public function gameElementInactive($id)
    {
        $element = GameElement::findOrFail($id);
        $element->status = 'inactive';
        $element->save();

        return redirect()->back()->with('success', 'تم إخفاء العنصر');
    }

    // إظهار عنصر
    public function gameElementActive($id)
    {
        $element = GameElement::findOrFail($id);
        $element->status = 'active';
        $element->save();

        return redirect()->back()->with('success', 'تم إظهار العنصر');
    }
}
