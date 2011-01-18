using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Xml;
using System.Xml.Serialization;
using System.Runtime.Serialization.Formatters.Soap;

namespace xDiffPatcher
{
    public class DiffProfile
    {
        public string Name { get; set; }
        public string FullPath { get; set; }
        public List<DiffProfileEntry> Entries { get; set; }

        public DiffProfile()
        {
            Entries = new List<DiffProfileEntry>();
        }

        static public DiffProfile Load(string filename)
        {
            FileStream str = null;

            try
            {
                str = new FileStream(filename, System.IO.FileMode.Open, System.IO.FileAccess.Read);
                XmlSerializer s = new XmlSerializer(typeof(DiffProfile));
                DiffProfile profile = (DiffProfile)s.Deserialize(str);
                str.Close();

                profile.FullPath = new FileInfo(filename).FullName;

                return profile;
            }
            catch (Exception)
            {
                //MessageBox.Show("Error deserializing last patches:\n" + ex.ToString());
                if (str != null)
                    str.Close();
                return null;
            }
        }

        private void parsePatch(DiffPatch p, bool ignoreApply = false)
        {
            if (!ignoreApply && !p.Apply)
                return;

            DiffProfileEntry entry = new DiffProfileEntry();
            entry.PatchID = p.ID;
            entry.PatchName = p.Name;

            foreach (DiffInput i in p.Inputs)
                entry.Inputs.Add(new DiffProfileInput() { name = i.Name, value = ((i.Value == null) ? "" : i.Value) });

            Entries.Add(entry);
        }

        public void Generate(DiffFile file)
        {
            Entries.Clear();

            foreach (DiffPatchBase b in file.xPatches.Values)
            {  
                if (b is DiffPatch)
                    parsePatch((DiffPatch)b);
                else if (b is DiffPatchGroup)
                    foreach (DiffPatch p in ((DiffPatchGroup)b).Patches)
                        parsePatch(p);
            }
        }

        public void Generate(PatchList list)
        {
            System.Windows.Forms.TreeNode node = list.Nodes[0];

            do 
            {
                if (node.Tag == null || !node.Checked)
                    continue;

                if (node.Tag is DiffPatchGroup && node.Nodes.Count > 0)
                {
                    foreach (System.Windows.Forms.TreeNode n in node.Nodes)
                        if (n.Checked && n.Tag is DiffPatch)
                            parsePatch((DiffPatch)n.Tag, true);
                }

                if (node.Tag is DiffPatch)
                {
                    DiffPatch p = (DiffPatch)node.Tag;

                    parsePatch(p, true);
                }
            } while((node = node.NextNode) != null);

        }

        public void Save(string filename)
        {
            System.IO.FileStream str = new System.IO.FileStream(filename, System.IO.FileMode.Create, System.IO.FileAccess.Write); 
            //SoapFormatter soap = new SoapFormatter();
            //IEnumerable<int> patches = rightPatches.Where(patch => patch != int.MaxValue);

            XmlSerializer s = new XmlSerializer(typeof(DiffProfile));
            s.Serialize(str, this);

            //soap.Serialize(str, Entries);
            str.Close();
        }

        public void Apply(ref PatchList lstPatches, ref DiffFile file)
        {
            // Clear patches and inputs
            foreach (DiffPatchBase b in file.xPatches.Values)
            {
                if (b is DiffPatchGroup)
                    foreach (DiffPatch p in ((DiffPatchGroup)b).Patches)
                    {
                        p.Apply = false;
                        foreach (DiffInput i in p.Inputs)
                            i.Value = null;
                    }
                else if (b is DiffPatch)
                {
                    ((DiffPatch)b).Apply = false;
                    foreach (DiffInput i in ((DiffPatch)b).Inputs)
                        i.Value = null;
                }
            }

            foreach (DiffProfileEntry entry in this.Entries)
            {
                DiffPatch patch = (DiffPatch)file.xPatches[entry.PatchID];
                ((DiffPatch)file.xPatches[entry.PatchID]).Apply = true;

                foreach (DiffProfileInput j in entry.Inputs)
                {
                    foreach (DiffInput k in patch.Inputs)
                        if (k.Name == j.name)
                            k.Value = j.value;
                }
            }

            // Lets just rebuild the whole thing =D
            lstPatches.Nodes.Clear();
            foreach (KeyValuePair<int, DiffPatchBase> p in file.xPatches)
            {
                if (p.Value is DiffPatchGroup)
                {
                    TreeNodeEx node = new TreeNodeEx(p.Value.Name);
                    node.Tag = file.xPatches[p.Value.ID]; //is this a reference or a copy? :x
                    node.ActAsRadioGroup = true;

                    foreach (DiffPatch p2 in ((DiffPatchGroup)p.Value).Patches)
                    {
                        TreeNodeEx n = new TreeNodeEx(p2.Name);
                        n.Tag = file.xPatches[p2.ID];
                        if (((DiffPatch)n.Tag).Apply)
                        {
                            n.Checked = true;
                            node.Checked = true;
                        }
                        node.Nodes.Add(n);
                    }
                    lstPatches.Nodes.Add(node);
                }
                else if (p.Value is DiffPatch && ((DiffPatch)p.Value).GroupID <= 0)
                {
                    TreeNodeEx node = new TreeNodeEx(p.Value.Name);
                    node.Tag = file.xPatches[p.Value.ID];
                    if (((DiffPatch)node.Tag).Apply)
                        node.Checked = true;
                    lstPatches.Nodes.Add(node);
                }
            }
        }
    }

    public struct DiffProfileInput
    {
        public string name;
        public string value;
    }

    public class DiffProfileEntry
    {
        public int PatchID { get; set; }
        public string PatchName { get; set; } //Not sure if this will be necessary
        public List<DiffProfileInput> Inputs { get; set; }

        public DiffProfileEntry()
        {
            Inputs = new List<DiffProfileInput>();
        }
    }    

}
