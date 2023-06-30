<?php

namespace App\Http\Controllers\Api;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObatResource;

class ObatController extends Controller
{
    # auth
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * @OA\Get(
     *      path="/api/obat",
     *      tags={"Obat"},
     *      summary="",
     *      description="Get all Data",
     *      operationId="obat_index",
     *      @OA\Parameter(
     *          name="per_page",
     *          description="Per Page value is number. ex : ?per_page=10",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="Page value is number. ex : ?page=10",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          description="Sort value is string with rule column-name:order. ex : ?sort=id:asc",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="where",
     *          description="Where value is object. ex : ?where{'name':'Izuchii','Izu':'2002-11-13'}",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="count",
     *          description="Count value is boolean. ex : ?count=true",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status"=true,
     *                  "message"="Get Data Successfull",
     *                  "data"={}
     *              }
     *          )
     *      )
     * )
     */

    # Index / Showing All Data
    public function index(Request $request)
    {
        # parameter
        $where = $request->has('where') ? $request->get('where') : '{}';
        $sort = $request->has('sort') ? $request->get('sort') : 'id:asc';
        $per_page = $request->has('per_page') ? $request->get('per_page') : 2;
        $page = $request->has('page') ? $request->get('page') : 1;
        $count = $request->has('count') ? $request->get('count') : false;
        $search = $request->has('search') ? $request->get('search') : '';

        # prepare parameter
        $sort = explode(':', $sort);
        $where = str_replace("'", "\"", $where);
        $where = json_decode($where, true);

        # query get
        $query = Obat::where([['id', '>', '0']]);

        # query where
        if($where) {
            foreach($where as $key => $value) {
                $query = $query->where([[$key, "=", $value]]);
            }
        }

        # query search
        if($search) {
            $query = $query->where([['nama', 'like', '%' . $search . '%']]);
            $query = $query->orWhere([['deskripsi', 'like', '%' . $search . '%']]);
            $query = $query->orWhere([['harga', 'like', '%' . $search . '%']]);
        }

        # variabel data
        $datas = [];

        # pagination
        $pagination = [];
        $pagination['page'] = (int)$page;
        $pagination['per_page'] = (int)$per_page;
        $pagination['total_data'] = $query->count('id');
        $pagination['total_page'] = ceil($pagination['total_data'] / $pagination['per_page']);

        # count
        if($count == true) {
            $query = $query->count('id');
            $datas['count'] = $query;
        } else { # Get Data
            $query = $query
                ->orderBy($sort[0], $sort[1])
                ->limit($per_page)
                ->offset(($page - 1) * $per_page)
                ->get()
                ->toArray();

            foreach($query as $qry) {
                $temp = $qry;

                $created_at_indo = Carbon::parse($temp['created_at']);
                $created_at_indo->locale('id')->settings(['formatFunction' => 'translatedFormat']);

                $temp['created_date_indo'] = $created_at_indo->format('l, d F Y H:i:s');
                array_push($datas, $temp);
            }
        }

        return new ObatResource(true, 'Get Data Successfull', $datas, $pagination);
    }

    /**
     * @OA\Get(
     *      path="/api/obat/{id}",
     *      tags={"Obat"},
     *      summary="",
     *      description="Get data by id",
     *      operationId="Obat_show",
     *      @OA\Parameter(
     *          name="id",
     *          description="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status"=true,
     *                  "message"="Get Data Successfull",
     *                  "data"={}
     *              }
     *          )
     *      )
     * )
     */

    # Show Data By ID
    public function show($id)
    {
        # query get by id
        $query = Obat::find($id);

        # variabel data
        $datas = $query;

        # pagination
        $pagination = [];

        return new ObatResource(true, "Get Data By Id Successfull", $datas, $pagination);
    }

    /**
     * @OA\Post(
     *      path="/api/obat",
     *      tags={"Obat"},
     *      summary="",
     *      description="Insert Data",
     *      operationId="obat_store",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="nama",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="tipe",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="gambar",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="deskripsi",
     *                      type="text",
     *                  ),
     *                  @OA\Property(
     *                      property="produksi",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="harga",
     *                      type="double",
     *                  ),
     *                  @OA\Property(
     *                      property="kadaluarsa",
     *                      type="date",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "success"=true,
     *                  "message"="Insert Data Successfull",
     *                  "data"={}
     *              }
     *          )
     *      )
     * )
     */

    # Insert Data
    public function store(Request $request)
    {
        # query insert
        $query = Obat::create($request->all());

        # variabel data
        $datas = $query;

        # pagination
        $pagination = [];

        return new ObatResource(true, "Insert Data Successfull", $datas, $pagination);
    }

    /**
     * @OA\Put(
     *      path="/api/obat/{id}",
     *      tags={"Obat"},
     *      summary="",
     *      description="Update Data",
     *      operationId="obat_update",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="nama",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="tipe",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="gambar",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="deskripsi",
     *                      type="text",
     *                  ),
     *                  @OA\Property(
     *                      property="produksi",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="harga",
     *                      type="double",
     *                  ),
     *                  @OA\Property(
     *                      property="kadaluarsa",
     *                      type="date",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "success"=true,
     *                  "message"="Update Data Successfull",
     *                  "data"={}
     *              }
     *          )
     *      )
     * )
     */

    # Put By Id / Update Data By Id
    public function update(Request $request, $id)
    {
        # query insert
        $query = Obat::findOrFail($id);
        $query = $query->update($request->all());

        # get after update
        $query = Obat::findOrFail($id);

        # variabel data
        $datas = $query;

        # pagination
        $pagination = [];

        return new ObatResource(true, "Update Data Successfull", $datas, $pagination);
    }

    /**
     * @OA\Delete(
     *      path="/api/obat/{id}",
     *      tags={"Obat"},
     *      summary="",
     *      description="Delete data",
     *      operationId="obat_destroy",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status"=true,
     *                  "message"="Delete Data Successfull",
     *                  "data"={}
     *              }
     *          )
     *      )
     * )
     */

    # Delete By Id
    public function destroy($id)
    {
        # query insert
        $query = Obat::findOrFail($id);
        $query = $query->delete();

        # variabel data
        $datas = $query;

        # pagination
        $pagination = [];

        return new ObatResource(true, "Delete Data Successfull", $datas, $pagination);
    }
}
