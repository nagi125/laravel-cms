@extends('admin._layouts.layout')
@section('title', $title ?? '')
@section('description', $description ?? '')
@section('content')
  <h4 class="c-grey-900 mT-10 mB-30">{{ $title }}</h4>
  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item  w-100">
      <div class="row gap-20">
        <!-- #Toatl Visits ==================== -->
        <div class='col-md-3'>
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Total Visits</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash"></span>
                </div>
                <div class="peer">
                  <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">+10%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Total Page Views ==================== -->
        <div class='col-md-3'>
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Total Page Views</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash2"></span>
                </div>
                <div class="peer">
                  <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">-7%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Unique Visitors ==================== -->
        <div class='col-md-3'>
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Unique Visitor</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash3"></span>
                </div>
                <div class="peer">
                  <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-purple-50 c-purple-500">~12%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Bounce Rate ==================== -->
        <div class='col-md-3'>
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Bounce Rate</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash4"></span>
                </div>
                <div class="peer">
                  <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="masonry-item col-md-6">
      <!-- #Todo ==================== -->
      <div class="bd bgc-white p-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <h6 class="lh-1">Todo List</h6>
          </div>
          <div class="layer w-100">
            <ul class="list-task list-group" data-role="tasklist">
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall1" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Call John for Dinner</span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall2" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall2" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Book Boss Flight</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-success lh-0 p-10">2 Days</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall3" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall3" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Hit the Gym</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-danger lh-0 p-10">3 Minutes</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall4" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall4" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Give Purchase Report</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-warning lh-0 p-10">not important</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall5" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall5" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Watch Game of Thrones Episode</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-info lh-0 p-10">Tomorrow</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall6" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall6" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Give Purchase report</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-success lh-0 p-10">Done</span>
                              </span>
                  </label>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="masonry-item col-md-6">
      <!-- #Todo ==================== -->
      <div class="bd bgc-white p-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <h6 class="lh-1">Todo List</h6>
          </div>
          <div class="layer w-100">
            <ul class="list-task list-group" data-role="tasklist">
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall1" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Call John for Dinner</span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall2" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall2" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Book Boss Flight</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-success lh-0 p-10">2 Days</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall3" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall3" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Hit the Gym</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-danger lh-0 p-10">3 Minutes</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall4" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall4" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Give Purchase Report</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-warning lh-0 p-10">not important</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall5" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall5" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Watch Game of Thrones Episode</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-info lh-0 p-10">Tomorrow</span>
                              </span>
                  </label>
                </div>
              </li>
              <li class="list-group-item bdw-0" data-role="task">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                  <input type="checkbox" id="inputCall6" name="inputCheckboxesCall" class="peer">
                  <label for="inputCall6" class=" peers peer-greed js-sb ai-c">
                    <span class="peer peer-greed">Give Purchase report</span>
                    <span class="peer">
                                <span class="badge badge-pill fl-r badge-success lh-0 p-10">Done</span>
                              </span>
                  </label>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="masonry-item col-md-12">
      <!-- #Sales Report ==================== -->
      <div class="bd bgc-white">
        <div class="layers">
          <div class="layer w-100 p-20">
            <h6 class="lh-1">Sales Report</h6>
          </div>
          <div class="layer w-100">
            <div class="bgc-light-blue-500 c-white p-20">
              <div class="peers ai-c jc-sb gap-40">
                <div class="peer peer-greed">
                  <h5>November 2017</h5>
                  <p class="mB-0">Sales Report</p>
                </div>
                <div class="peer">
                  <h3 class="text-right">$6,000</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive p-20">
              <table class="table">
                <thead>
                <tr>
                  <th class=" bdwT-0">Name</th>
                  <th class=" bdwT-0">Status</th>
                  <th class=" bdwT-0">Date</th>
                  <th class=" bdwT-0">Price</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td class="fw-600">Item #1 Name</td>
                  <td><span class="badge bgc-red-50 c-red-700 p-10 lh-0 tt-c badge-pill">Unavailable</span> </td>
                  <td>Nov 18</td>
                  <td><span class="text-success">$12</span></td>
                </tr>
                <tr>
                  <td class="fw-600">Item #2 Name</td>
                  <td><span class="badge bgc-deep-purple-50 c-deep-purple-700 p-10 lh-0 tt-c badge-pill">New</span></td>
                  <td>Nov 19</td>
                  <td><span class="text-info">$34</span></td>
                </tr>
                <tr>
                  <td class="fw-600">Item #3 Name</td>
                  <td><span class="badge bgc-pink-50 c-pink-700 p-10 lh-0 tt-c badge-pill">New</span></td>
                  <td>Nov 20</td>
                  <td><span class="text-danger">-$45</span></td>
                </tr>
                <tr>
                  <td class="fw-600">Item #4 Name</td>
                  <td><span class="badge bgc-green-50 c-green-700 p-10 lh-0 tt-c badge-pill">Unavailable</span></td>
                  <td>Nov 21</td>
                  <td><span class="text-success">$65</span></td>
                </tr>
                <tr>
                  <td class="fw-600">Item #5 Name</td>
                  <td><span class="badge bgc-red-50 c-red-700 p-10 lh-0 tt-c badge-pill">Used</span></td>
                  <td>Nov 22</td>
                  <td><span class="text-success">$78</span></td>
                </tr>
                <tr>
                  <td class="fw-600">Item #6 Name</td>
                  <td><span class="badge bgc-orange-50 c-orange-700 p-10 lh-0 tt-c badge-pill">Used</span> </td>
                  <td>Nov 23</td>
                  <td><span class="text-danger">-$88</span></td>
                </tr>
                <tr>
                  <td class="fw-600">Item #7 Name</td>
                  <td><span class="badge bgc-yellow-50 c-yellow-700 p-10 lh-0 tt-c badge-pill">Old</span></td>
                  <td>Nov 22</td>
                  <td><span class="text-success">$56</span></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="ta-c bdT w-100 p-20">
          <a href="#">Check all the sales</a>
        </div>
      </div>
    </div>
  </div>
@endsection
