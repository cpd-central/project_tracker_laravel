<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\newproject;

class newprojectcontroller extends Controller
{
    //
	public function create()
    {
        return view('newprojectcreate');
    }
	
	public function store(Request $request)
    {
        $newproject = new newproject();
        $newproject->cegproposalauthor= $request->get('cegproposalauthor');
        $newproject->projectname= $request->get('projectname');
        $newproject->clientcontactname= $request->get('clientcontactname');
        $newproject->clientcompany = $request->get('clientcompany');        
        $newproject->mwsize = $request->get('mwsize');        
        $newproject->voltage = $request->get('voltage');        
        $newproject->dollarvalueinhouse = $request->get('dollarvalueinhouse');        
        $newproject->dateproposed = $request->get('dateproposed');        
        $newproject->datentp = $request->get('datentp');        
        $newproject->dateenergization = $request->get('dateenergization');        

        $newproject->projecttypewind = $request->get('projectrypewind');        
        $newproject->projecttypesolar = $request->get('projectypesolar');        
        $newproject->projecttypestorage = $request->get('projecttypestorage');        
        $newproject->projecttypearray = $request->get('projecttypearray');        
        $newproject->projecttypetransmission = $request->get('projecttypetransmission');        
        $newproject->projecttypesubstation = $request->get('projecttypesubstation');        
        $newproject->projecttypedistribution = $request->get('projecttypedistribution');        
        $newproject->projecttypescada = $request->get('projectypescada');        
        $newproject->projecttypestudy = $request->get('projectypestudy');        
	
        $newproject->electricalengineering = $request->get('electricalengineering');        
        $newproject->civilengineering = $request->get('civilengineering');        
        $newproject->structuralmechanicalengineering = $request->get('structuralmechanicalengineering');        
        $newproject->procurement = $request->get('procurement');        
        $newproject->construction = $request->get('construction');        

        $newproject->sel1 = $request->get('sel1');        
        $newproject->projectcode = $request->get('projectcode');        
        $newproject->projectmanager = $request->get('projectmanager');        
        $newproject->save();
        return redirect('newproject')->with('success', 'newproject has been successfully added');
    }

    public function update(Request $request, $id)
    {
        $newproject = newproject::find($id);
        $newproject->cegproposalauthor= $request->get('cegproposalauthor');
        $newproject->projectname= $request->get('projectname');
        $newproject->clientcontactname= $request->get('clientcontactname');
        $newproject->clientcompany = $request->get('clientcompany');        
        $newproject->mwsize = $request->get('mwsize');        
        $newproject->voltage = $request->get('voltage');        
        $newproject->dollarvalueinhouse = $request->get('dollarvalueinhouse');        
        $newproject->dateproposed = $request->get('dateproposed');        
        $newproject->datentp = $request->get('datentp');        
        $newproject->dateenergization = $request->get('dateenergization');        

        $newproject->projecttypewind = $request->get('projectrypewind');        
        $newproject->projecttypesolar = $request->get('projectypesolar');        
        $newproject->projecttypestorage = $request->get('projecttypestorage');        
        $newproject->projecttypearray = $request->get('projecttypearray');        
        $newproject->projecttypetransmission = $request->get('projecttypetransmission');        
        $newproject->projecttypesubstation = $request->get('projecttypesubstation');        
        $newproject->projecttypedistribution = $request->get('projecttypedistribution');        
        $newproject->projecttypescada = $request->get('projectypescada');        
        $newproject->projecttypestudy = $request->get('projectypestudy');        
    
        $newproject->electricalengineering = $request->get('electricalengineering');        
        $newproject->civilengineering = $request->get('civilengineering');        
        $newproject->structuralmechanicalengineering = $request->get('structuralmechanicalengineering');        
        $newproject->procurement = $request->get('procurement');        
        $newproject->construction = $request->get('construction');        

        $newproject->sel1 = $request->get('sel1');        
        $newproject->projectcode = $request->get('projectcode');        
        $newproject->projectmanager = $request->get('projectmanager');        
        $newproject->save();
        return redirect('allprojects')->with('success', 'newproject has been successfully added');
    }




	public function index()
    {
        $newprojects=newproject::all();
        return view('newprojectindex',compact('newprojects'));
    }

    public function edit($id)   
    {
        $newproject = newproject::find($id);
        return view('newprojectedit',compact('newproject','id'));
    }

    public function destroy($id)
    {
        $newproject = newproject::find($id);
        $newproject->delete();
        return redirect('allprojects')->with('success','Newproject has been  deleted');
    }
	
}

